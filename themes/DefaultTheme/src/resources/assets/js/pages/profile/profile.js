// Declare $ variable
const $ = window.jQuery; // Assuming jQuery is available globally

class InstagramProfile {
  constructor() {
    this.isFollowing = false;
    this.isOwnProfile = this.currentUserId === this.profileUserId;
    this.selectedMedia = [];
    this.profileData = {};
    this.isLoading = false;

    // ---- وضعیت‌ها
    this.qrCode = null;            // ✅ این رو با Base64 پر می‌کنیم
    this.latitude = null;
    this.longitude = null;

    // آیدی پروفایل
    const cleanPath = window.location.pathname.replace(/\/+$/, "");
    const pathParts = cleanPath.split("/");
    this.profileId = pathParts[pathParts.length - 1] || null;

    this.initializeElements();
    this.bindEvents();
    this.updateUI();

    // ✅ تلاش برای مقداردهی اولیه از URL/DOM/Storage
    this.hydrateQRCode();

    // ✅ سپس fetch (سرور اگر qrCodeBase64 بده، همونو مرجع می‌گیریم)
    this.fetchProfileDataFromAPI();
  }


  // --- NEW: setters & getters برای استفاده در فانکشن‌های دیگر ---
    setQRCode(qr) {
    const val = (qr ?? "").toString().trim();
    this.qrCode = val || null;

     if (this.qrCode) {
      // اگر احتمالاً Base64 است، همان را ذخیره کن تا بعد از رفرش هم داشته باشیم
      localStorage.setItem("qrCodeBase64", this.qrCode);
     } else {
      localStorage.removeItem("qrCodeBase64");
      localStorage.removeItem("qrCode");
     }
   }
	  // --- NEW: تلاش برای دریافت QR از منابع مختلف (سازگاری قدیمی)
  hydrateQRCode() {
    try {
      const url = new URL(window.location.href);
      const fromQuery = url.searchParams.get("qr") || url.searchParams.get("qr_code");
      const fromDataset = document.body?.dataset?.qrCode || null;
      const fromStorage = localStorage.getItem("qrCodeBase64") || localStorage.getItem("qrCode");

      const resolved = (fromQuery || fromDataset || fromStorage || "").trim();
      if (resolved) {
        // اگر از Storage اومده و شبیه Base64 بود، همون رو نگه می‌داریم
        this.qrCode = resolved;
      }
    } catch (e) {}
  }

  setLocation(lat, lng) { this.latitude = lat; this.longitude = lng; }

  getQRCode() { return this.qrCode; }
  getLatitude() { return this.latitude; }
  getLongitude() { return this.longitude; }

  fetchProfileDataFromAPI() {
    if (!this.profileId) {
      console.error("❌ Profile ID not found in URL.");
      return;
    }
    this.showLoadingState();
    $.ajax({
      url: `https://halvaa.net/user/profile/${this.profileId}`, // Your actual API endpoint
      method: "GET",
      dataType: "json",
      // --- NEW: ارسال پارامترها همراه ریکوئست ---
      data: {
        qr_code: this.qrCode,   // اگر بک‌اند اسم دیگری می‌خواهد، کلید را عوض کن
        latitude: this.latitude,
        longitude: this.longitude,
      },
  success: (response) => {
    const user = response.user;

    // تصویر پروفایل (مثل قبل با اولویت profile_image.image)
    let profileImageUrl = "/cityholder.svg?height=90&width=90";
    if (user.profile_image?.image) {
      profileImageUrl = `https://halvaa.net/${user.profile_image.image}`;
    } else if (response.profile_image) {
      // بک‌اند جدا هم داده
      const p = response.profile_image.startsWith("/")
        ? `https://halvaa.net${response.profile_image}`
        : response.profile_image;
      profileImageUrl = p;
    } else if (user.profile_photo_url) {
      profileImageUrl = user.profile_photo_url;
    }

    // ✅ QR از سرور (Base64)
    if (response.qrCodeBase64) {
      this.qrCode = response.qrCodeBase64; // <-- مهم: حالا window.instagramProfile.qrCode نال نیست
      localStorage.setItem("qrCodeBase64", this.qrCode);
    }

    // ✅ lat/lng اگر قبلاً نداشتیم از سرور ست کنیم
    if (this.latitude == null && user?.latitude) this.latitude = Number(user.latitude);
    if (this.longitude == null && user?.longitude) this.longitude = Number(user.longitude);

    // ذخیره پروفایل
    this.profileData = {
      first_name: user.first_name || "",
      last_name: user.last_name || "",
      username: user.username || "",
      profile_image: profileImageUrl,
      profile_image_object: user.profile_image || null,
      national_code: user.national_code || "",
      email: user.email || "",
      birth: user.birth || "",
      death: user.death || "",
      is_private: !!user.is_private,
      birth_city_id: user.birth_city_id || "",
      death_city_id: user.death_city_id || "",
      bio: user.bio || "",
      // افزوده‌ها (از ریسپانس تو)
      followersCount: response.followersCount ?? 0,
      followingCount: response.followingCount ?? 0,
      postCount: response.postCount ?? 0,
      profile_views: response.profileview ?? user.profile_views ?? 0,
      hasQrCodeProduct: !!response.hasQrCodeProduct,
      birth_city: user.birth_city || null,
      death_city: user.death_city || null,
      family_tree: response.family_tree || user.family_tree || null,
    };

    this.updateProfileUI();
    this.updateQRUI(); // ✅ رندر QR اگر img داریم
    this.hideLoadingState();
  },
   error: (xhr) => {
        this.hideLoadingState();
        this.showErrorMessage("خطا در دریافت اطلاعات پروفایل.");
        console.error("Profile fetch error:", xhr.responseText);
        // Set minimal fallback data for UI
        this.profileData = {
          bio: "اطلاعات پروفایل قابل دسترس نیست",
          profile_image: "/cityholder.svg?height=90&width=90",
        };
        this.updateProfileUI();
      },
    });
  }

  showLoadingState() {
    this.isLoading = true;
    this.$profileBio.text("در حال بارگذاری اطلاعات...");
    this.$profilePicture.css("opacity", "0.5");
  }

  hideLoadingState() {
    this.isLoading = false;
    this.$profilePicture.css("opacity", "1");
  }

  updateProfileUI() {
    this.$profileBio.text(this.profileData.bio);
    this.$profilePicture.attr("src", this.profileData.profile_image);
  }
  updateQRUI() {
    if (this.$qrImage && this.$qrImage.length && this.qrCode) {
      // اگر مقدار، فقط Base64 خالص باشد:
      const src = this.qrCode.startsWith("data:image")
        ? this.qrCode
        : `data:image/png;base64,${this.qrCode}`;
      this.$qrImage.attr("src", src).show();
    }
  }	

  initializeElements() {
    // Cache jQuery objects for better performance
    this.$profilePicture = $("#profilePicture");
    this.$profileBio = $("#profileBio");
    this.$buttonSection = $("#buttonSection");
    this.$profileToggle = $("#profileToggle");
    this.$followStatus = $("#followStatus");

    // Modal elements
    this.$editModal = $("#editModal");
    this.$closeModal = $("#closeModal");
    this.$edit_first_name = $("#edit_first_name");
    this.$edit_last_name = $("#edit_last_name");
    this.$edit_national_code = $("#edit_national_code");
    this.$edit_email = $("#edit_email");
    this.$edit_birth = $("#edit_birth");
    this.$edit_death = $("#edit_death");
    this.$edit_birth_city_id = $("#edit_birth_city_id");
    this.$edit_death_city_id = $("#edit_death_city_id");
    this.$editBio = $("#editBio");
    this.$profilePictureInput = $("#profilePictureInput");
    this.$previewPicture = $("#previewPicture");
    this.$cancelEdit = $("#cancelEdit");
    this.$saveProfile = $("#saveProfile");

    // Create post elements
    this.$createPostModal = $("#createPostModal");
    this.$closeCreatePostModal = $("#closeCreatePostModal");
    this.$postForm = $("#postForm");
    this.$postDescription = $("#postDescription");
    this.$fileInput = $("#fileInput");
    this.$uploadArea = $("#uploadArea");
    this.$selectMediaBtn = $("#selectMediaBtn");
    this.$mediaPreviewContainer = $("#mediaPreviewContainer");
    this.$mediaCount = $("#mediaCount");
    this.$richTextButtons = $(".rich-text-toolbar button");
	this.$qrImage = $("#qrImage"); // ✅ اختیاری: اگر img#qrImage داری
  }

  bindEvents() {
    // Profile toggle
    this.$profileToggle.on("click", () => this.toggleProfileView());

    // Modal events
    this.$closeModal.on("click", () => this.closeEditModal());
    this.$cancelEdit.on("click", () => this.closeEditModal());
    this.$saveProfile.on("click", () => this.saveProfileChanges());

    // Profile picture events
    this.$profilePictureInput.on("change", (e) => this.handleImageUpload(e));
    $(".current-picture").on("click", () => this.$profilePictureInput.click());

    // Close modal when clicking outside
    this.$editModal.on("click", (e) => {
      if (e.target === this.$editModal[0]) {
        this.closeEditModal();
      }
    });

    // Create post modal events
    this.$closeCreatePostModal.on("click", () => this.closeCreatePostModal());
    this.$createPostModal.on("click", (e) => {
      if (e.target === this.$createPostModal[0]) {
        this.closeCreatePostModal();
      }
    });

    // Rich text editor events
    /*this.$richTextButtons.on("click", (e) => {
           e.preventDefault()
           const command = $(e.currentTarget).data("command")
           document.execCommand(command, false, null)
           $(e.currentTarget).toggleClass("active")
           this.$postDescription.focus()
         }) */

    // File input and upload events
    this.$fileInput.on("change", (e) => this.handleFiles(e.target.files));
    this.$selectMediaBtn.on("click", (e) => {
      e.preventDefault();
      this.$fileInput.click();
    });
    this.$uploadArea.on("click", (e) => {
      e.preventDefault();
      this.$fileInput.click();
    });

    // Drag and drop events
    this.$uploadArea.on("dragover", (e) => {
      e.preventDefault();
      $(e.currentTarget).addClass("dragover");
    });
    this.$uploadArea.on("dragleave", (e) => {
      e.preventDefault();
      $(e.currentTarget).removeClass("dragover");
    });
    this.$uploadArea.on("drop", (e) => {
      e.preventDefault();
      $(e.currentTarget).removeClass("dragover");
      this.handleFiles(e.originalEvent.dataTransfer.files);
    });

    // Form submission
    this.$postForm.on("submit", (e) => {
      e.preventDefault();
      this.handlePostSubmission();
    });

    this.$buttonSection
      .find('[data-action="edit"]')
      .on("click", () => this.openEditModal());
    this.$buttonSection
      .find('[data-action="create-post"]')
      .on("click", () => this.openCreatePostModal());

    $("#followBtn").on("click", () => {
      this.toggleFollow();
      this.updateFollowButton();
    });

    $("#newCustomerToggle").on("change", (e) => {
      const isChecked = e.target.checked;
      this.isPrivate = isChecked;
      const $label = $(e.currentTarget)
        .closest(".order-toggle-wrapper")
        .find(".order-toggle-label");
      $label.text(this.isPrivate ? "خصوصی" : "عمومی");
    });
  }

  updateFollowButton() {
    const $btn = $("#followBtn");
    if (this.isFollowing) {
      $btn.text("لغو دنبال کردن").removeClass("follow").addClass("unfollow");
    } else {
      $btn.text("دنبال کردن").removeClass("unfollow").addClass("follow");
    }
  }

  toggleProfileView() {
    this.isOwnProfile = !this.isOwnProfile;
    if (this.isOwnProfile) {
      this.isFollowing = false;
    }
    this.updateUI();
  }

  updateUI() {
    this.updateToggleButton();
  }

  updateToggleButton() {
    const text = this.isOwnProfile
      ? "نمایش به‌صورت کاربر دیگر "
      : "نمایش به‌صورت صاحب پروفایل";
    this.$profileToggle.text(text);
  }

  toggleFollow() {
    this.isFollowing = !this.isFollowing;
    this.updateFollowButton();
  }

  initBioCounter() {
    const MAX_BIO_LEN = 100;
    this.$bioCounter = $(".js-bio-counter");

    this.$editBio.on("input", () => {
      let val = this.$editBio.val() || "";
      if (val.length > MAX_BIO_LEN) {
        val = val.slice(0, MAX_BIO_LEN);
        this.$editBio.val(val);
      }
      const len = val.length;
      if (this.$bioCounter.length) {
        this.$bioCounter.text(`${len}/${MAX_BIO_LEN}`);
        this.$bioCounter.css("color", len >= MAX_BIO_LEN ? "red" : "");
      }
    });

    // Trigger initial count
    this.$editBio.trigger("input");
  }

  openEditModal() {
    // Populate ALL form fields with current data
    this.$edit_first_name.val(this.profileData.first_name || "");
    this.$edit_last_name.val(this.profileData.last_name || "");
    this.$edit_national_code.val(this.profileData.national_code || "");
    this.$edit_email.val(this.profileData.email || "");
    this.$edit_birth.val(this.profileData.birth || "");
    this.$edit_death.val(this.profileData.death || "");
    this.$edit_birth_city_id.val(this.profileData.birth_city_id || "");
    this.$edit_death_city_id.val(this.profileData.death_city_id || "");
    this.$editBio.val(this.profileData.bio || "");

    // FIXED: Set the privacy toggle and preview picture correctly
    this.isPrivate = this.profileData.is_private || false;
    $("#newCustomerToggle").prop("checked", this.isPrivate);
    $(".order-toggle-label").text(this.isPrivate ? "خصوصی" : "عمومی");

    // FIXED: Ensure the preview picture shows the current profile image
    // console.log("🖼️ Setting preview picture to:", this.profileData.profile_image)
    this.$previewPicture.attr("src", this.profileData.profile_image);

    // Clear any previous file selection
    this.$profilePictureInput.val("");

    this.$editModal.addClass("active");
    $("body").css("overflow", "hidden");

    this.initBioCounter();
  }

  closeEditModal() {
    this.$editModal.removeClass("active");
    $("body").css("overflow", "auto");
    this.$profilePictureInput.val("");
    // FIXED: Reset preview picture to current profile image when closing
    this.$previewPicture.attr("src", this.profileData.profile_image);
  }

  handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
      // Validate file type
      if (!file.type.startsWith("image/")) {
        this.showErrorMessage("لطفاً یک فایل تصویر معتبر انتخاب کنید.");
        return;
      }
      // Validate file size (max 2MB)
      if (file.size > 2 * 1024 * 1024) {
        this.showErrorMessage(
          "لطفاً تصویری با حجم کمتر از 2 مگابایت انتخاب کنید."
        );
        return;
      }
      // Show loading state
      this.$previewPicture.css("opacity", "0.5");
      const reader = new FileReader();
      reader.onload = (e) => {
        this.$previewPicture.attr("src", e.target.result).css("opacity", "1");
        this.showInfoMessage(
          "پیش‌ نمایش تصویر به‌روزرسانی شد! برای اعمال، روی «ذخیره تغییرات» کلیک کنید."
        );
      };
      reader.onerror = () => {
        this.showErrorMessage("خطا در خواندن فایل تصویر.");
        this.$previewPicture.css("opacity", "1");
      };
      reader.readAsDataURL(file);
    }
  }

  // FIXED: saveProfileChanges with 100-char limit + defensive live counter wiring
  saveProfileChanges() {
    const MAX_BIO_LEN = 100;

    // Ensure counter element exists (expects <small class="js-bio-counter"></small> near the textarea)
    if (!this.$bioCounter) {
      this.$bioCounter = $(".js-bio-counter");
    }

    // Defensive: wire live counter if not already wired.
    if (!this._wiredBioCounter && this.$editBio && this.$editBio.length) {
      this._wiredBioCounter = true;
      this.$editBio
        .on("input", () => {
          let val = this.$editBio.val() || "";
          if (val.length > MAX_BIO_LEN) {
            val = val.slice(0, MAX_BIO_LEN);
            this.$editBio.val(val);
          }
          const len = val.length;
          if (this.$bioCounter && this.$bioCounter.length) {
            this.$bioCounter.text(`${len}/${MAX_BIO_LEN}`);
            // turn red at the limit
            this.$bioCounter.css("color", len >= MAX_BIO_LEN ? "red" : "");
          }
        })
        .trigger("input");
    }

    const first_name = this.$edit_first_name.val();
    const last_name = this.$edit_last_name.val();
    const national_code = this.$edit_national_code.val();
    const email = this.$edit_email.val();
    const birth = this.$edit_birth.val();
    const death = this.$edit_death.val();
    const birth_city_id = this.$edit_birth_city_id.val();
    const death_city_id = this.$edit_death_city_id.val();
    const bio = (this.$editBio.val() || "").trim();

    // Hard validation (server safety)
    if (bio.length > MAX_BIO_LEN) {
      this.showErrorMessage(
        `بیوگرافی باید حداکثر ${MAX_BIO_LEN} کاراکتر باشد.`
      );
      // also reflect in the counter immediately
      if (this.$bioCounter && this.$bioCounter.length) {
        this.$bioCounter.text(
          `${Math.min(bio.length, MAX_BIO_LEN)}/${MAX_BIO_LEN}`
        );
        this.$bioCounter.css("color", "red");
      }
      return;
    }

    // Show loading state
    this.$saveProfile
      .addClass("loading")
      .text("در حال ذخیره...")
      .prop("disabled", true);

    // Check if profile image was changed
    const profileImageFile = this.$profilePictureInput[0]?.files?.[0];

    // Create FormData with the EXACT field names your API expects
    const formData = new FormData();
    formData.append("first_name", first_name);
    formData.append("last_name", last_name);
    formData.append("username", this.profileData.username);
    formData.append("national_code", national_code);
    formData.append("email", email);
    formData.append("birth", birth);
    formData.append("death", death);
    formData.append("is_private", this.isPrivate ? 1 : 0);
    formData.append("birth_city_id", birth_city_id);
    formData.append("death_city_id", death_city_id);
    formData.append("bio", bio);

    // Add profile image with the correct field name
    if (profileImageFile) {
      formData.append("profile_image", profileImageFile);
    }

    $.ajax({
      url: "https://halvaa.net/userprofile/update",
      method: "POST",
      processData: false,
      contentType: false,
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      data: formData,
      success: (response) => {
        let updatedProfileImageUrl = this.profileData.profile_image; // fallback

        if (response.user?.profile_image?.image) {
          updatedProfileImageUrl = `https://halvaa.net/${response.user.profile_image.image}`;
        } else if (response.user?.profile_photo_url) {
          updatedProfileImageUrl = response.user.profile_photo_url;
        }

        // Update local profile data with the response
        this.profileData = {
          first_name: response.user.first_name || first_name,
          last_name: response.user.last_name || last_name,
          username: response.user.username || this.profileData.username,
          profile_image: updatedProfileImageUrl,
          profile_image_object:
            response.user.profile_image ||
            this.profileData.profile_image_object,
          national_code: response.user.national_code || national_code,
          email: response.user.email || email,
          birth: response.user.birth || birth,
          death: response.user.death || death,
          is_private:
            response.user.is_private !== undefined
              ? response.user.is_private
              : this.isPrivate,
          birth_city_id: response.user.birth_city_id || birth_city_id,
          death_city_id: response.user.death_city_id || death_city_id,
          bio: response.user.bio || bio,
        };

        // Update UI with new data
        this.updateProfileUI();
        this.showSuccessMessage("پروفایل با موفقیت بروزرسانی شد");
        this.closeEditModal();
      },
      error: (xhr) => {
        console.error("❌ Profile update failed:", xhr.responseText);
        console.error("❌ Status:", xhr.status);
        console.error("❌ Response:", xhr.responseJSON);

        const errorMsg =
          xhr.responseJSON?.message ||
          xhr.responseJSON?.error ||
          `خطا در ذخیره تغییرات پروفایل (${xhr.status})`;
        this.showErrorMessage(errorMsg);
      },
      complete: () => {
        this.resetSaveButton();
      },
    });
  }

  resetSaveButton() {
    this.$saveProfile
      .removeClass("loading")
      .text("ذخیره تغییرات")
      .prop("disabled", false);
  }

  openCreatePostModal() {
    this.$createPostModal.addClass("active");
    $("body").css("overflow", "hidden");
    this.selectedMedia = [];
    this.renderMediaPreviews();
  }

  closeCreatePostModal() {
    this.$createPostModal.removeClass("active");
    $("body").css("overflow", "auto");
    this.resetCreatePostForm();
  }

  handleFiles(files) {
    Array.from(files).forEach((file) => {
      const isDuplicate = this.selectedMedia.some(
        (media) => media.name === file.name && media.size === file.size
      );
      if (isDuplicate) {
        this.showInfoMessage(`فایل "${file.name}" قبلاً اضافه شده است.`);
        return;
      }
      if (file.type.startsWith("image/")) {
        if (file.size > 2 * 1024 * 1024) {
          this.showErrorMessage(
            `حجم عکس "${file.name}" نباید بیشتر از ۲ مگابایت باشد.`
          );
          return;
        }
        this.processFile(file);
      } else if (file.type.startsWith("video/")) {
        if (file.size > 10 * 1024 * 1024) {
          this.showErrorMessage(
            `حجم ویدیو "${file.name}" نباید بیشتر از 10 مگابایت باشد.`
          );
          return;
        }
        this.processFile(file);
      } else {
        this.showErrorMessage(`نوع فایل پشتیبانی نمی‌شود: ${file.name}`);
      }
    });
  }

  processFile(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      const mediaData = {
        id: Date.now() + Math.random(),
        file: file,
        src: e.target.result,
        name: file.name,
        type: file.type.startsWith("image/") ? "image" : "video",
        size: file.size,
        duration: null,
      };
      if (mediaData.type === "video") {
        this.getVideoDuration(mediaData);
      } else {
        this.selectedMedia.push(mediaData);
        this.renderMediaPreviews();
      }
    };
    reader.readAsDataURL(file);
  }

  getVideoDuration(mediaData) {
    const video = $("<video>")[0];
    video.src = mediaData.src;
    video.onloadedmetadata = () => {
      mediaData.duration = video.duration;
      this.selectedMedia.push(mediaData);
      this.renderMediaPreviews();
    };
  }

  renderMediaPreviews() {
    this.$mediaPreviewContainer.empty();
    if (this.selectedMedia.length > 0) {
      this.$mediaPreviewContainer.show();
      this.$uploadArea.hide();
      this.selectedMedia.forEach((media) => {
        const $previewElement = this.createMediaPreview(media);
        this.$mediaPreviewContainer.append($previewElement);
      });
      // Add "Add More" button
      const $addMoreButton = this.createAddMoreButton();
      this.$mediaPreviewContainer.append($addMoreButton);
      this.updateMediaCount();
    } else {
      this.$mediaPreviewContainer.hide();
      this.$uploadArea.show();
      this.$mediaCount.hide();
    }
  }

  createMediaPreview(media) {
    const $previewDiv = $("<div>")
      .addClass("media-preview")
      .attr("data-id", media.id);

    if (media.type === "image") {
      $previewDiv.html(`
                <img src="${media.src}" alt="${media.name}">
                <div class="media-type-badge">IMG</div>
                <button type="button" class="remove-media" data-media-id="${media.id}">
                    <i class="ri-close-line"></i>
                </button>
            `);
    } else {
      const duration = media.duration
        ? this.formatDuration(media.duration)
        : "";
      $previewDiv.html(`
                <video src="${media.src}" muted preload="metadata"></video>
                <div class="media-type-badge">VIDEO</div>
                ${
                  duration
                    ? `<div class="video-duration">${duration}</div>`
                    : ""
                }
                <button type="button" class="video-overlay" data-media-id="${
                  media.id
                }">
                    <i class="ri-play-line"></i>
                </button>
                <button type="button" class="remove-media" data-media-id="${
                  media.id
                }">
                    <i class="ri-close-line"></i>
                </button>
            `);
    }

    // Bind events for this preview
    $previewDiv.find(".remove-media").on("click", (e) => {
      const mediaId = $(e.currentTarget).data("media-id");
      this.removeMedia(mediaId);
    });

    $previewDiv.find(".video-overlay").on("click", (e) => {
      const mediaId = $(e.currentTarget).data("media-id");
      this.toggleVideo(mediaId);
    });

    return $previewDiv;
  }

  createAddMoreButton() {
    const $addMoreDiv = $("<div>")
      .addClass("media-preview")
      .css({
        border: "2px dashed #dbdbdb",
        cursor: "pointer",
        display: "flex",
        "align-items": "center",
        "justify-content": "center",
      })
      .html(
        `
                <div style="text-align: center;">
                    <i class="ri-add-line" style="font-size: 24px; color: #8e8e8e; margin-bottom: 8px; display: block;"></i>
                    <p style="color: #8e8e8e; margin: 0; font-size: 12px;">بیشتر اضافه کنید</p>
                </div>
            `
      )
      .on("click", () => {
        this.$fileInput.val("");
        this.$fileInput.click();
      });

    return $addMoreDiv;
  }

  removeMedia(mediaId) {
    this.selectedMedia = this.selectedMedia.filter(
      (media) => media.id != mediaId
    );
    this.renderMediaPreviews();
  }

  toggleVideo(mediaId) {
    const $mediaPreview = $(`[data-id="${mediaId}"]`);
    const video = $mediaPreview.find("video")[0];
    const $overlay = $mediaPreview.find(".video-overlay");
    if (video.paused) {
      video.play();
      $overlay.addClass("playing");
    } else {
      video.pause();
      $overlay.removeClass("playing");
    }
  }

  updateMediaCount() {
    const imageCount = this.selectedMedia.filter(
      (m) => m.type === "image"
    ).length;
    const videoCount = this.selectedMedia.filter(
      (m) => m.type === "video"
    ).length;
    const totalSize = this.selectedMedia.reduce((sum, m) => sum + m.size, 0);

    let countText = "";
    if (imageCount > 0 && videoCount > 0) {
      countText = `${imageCount} عکس و ${videoCount} ویدیو انتخاب شده است`;
    } else if (imageCount > 0) {
      countText = `${imageCount} عکس انتخاب شده است`;
    } else if (videoCount > 0) {
      countText = `${videoCount} ویدیو انتخاب شده است`;
    }

    countText += ` (${this.formatFileSize(totalSize)})`;
    this.$mediaCount.text(countText).show();
  }

  formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return (
      Number.parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
    );
  }

  formatDuration(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs.toString().padStart(2, "0")}`;
  }

  handlePostSubmission() {
    const description = this.$postDescription.val().trim();
    if (!description) {
      this.showErrorMessage("لطفاً توضیحات را وارد کنید.");
      return;
    }
    const totalFiles = this.selectedMedia.length;
    if (totalFiles === 0) {
      this.showErrorMessage("حداقل یک فایل رسانه‌ای انتخاب کنید.");
      return;
    }

    // Create FormData object
    const formData = new FormData();
    formData.append("description", description);

    // Separate arrays for images and videos
    const imageFiles = [];
    const videoFiles = [];

    // Categorize files
    this.selectedMedia.forEach((media) => {
      if (media.type === "image") imageFiles.push(media.file);
      else if (media.type === "video") videoFiles.push(media.file);
    });

    // Append arrays to FormData
    imageFiles.forEach((file, index) => {
      formData.append(`images[${index}]`, file);
    });
    videoFiles.forEach((file, index) => {
      formData.append(`videos[${index}]`, file);
    });

    //console.log("📤 Creating post with FormData")

    $.ajax({
      url: "https://halvaa.net/user-posts",
      method: "POST",
      contentType: false,
      processData: false,
      data: formData,
      success: (response) => {
        // console.log("✅ Post created successfully:", response)
        this.showSuccessMessage("پست با موفقیت ارسال شد");
        this.closeCreatePostModal();
      },
      error: (xhr) => {
        console.error("❌ Post creation failed:", xhr.responseText);
        // Improved error message
        const errorMsg =
          xhr.responseJSON?.errors?.images?.[0] ||
          xhr.responseJSON?.message ||
          "خطا در ارسال پست";
        this.showErrorMessage(errorMsg);
      },
    });
  }

  resetCreatePostForm() {
    this.$postDescription.html("");
    this.$fileInput.val("");
    this.selectedMedia = [];
    this.$mediaPreviewContainer.hide();
    this.$uploadArea.show();
    this.$mediaCount.hide();
    this.$richTextButtons.removeClass("active");
  }

  showErrorMessage(message) {
    this.showMessage(message, "error");
  }

  showInfoMessage(message) {
    this.showMessage(message, "info");
  }

  showSuccessMessage(message) {
    this.showMessage(message, "success");
  }

  showMessage(text, type = "success") {
    // Remove any existing messages
    $(".toast-message").remove();

    const colors = {
      success: { bg: "#4CAF50", shadow: "rgba(76, 175, 80, 0.3)" },
      error: { bg: "#f44336", shadow: "rgba(244, 67, 54, 0.3)" },
      info: { bg: "#2196F3", shadow: "rgba(33, 150, 243, 0.3)" },
    };

    const $message = $("<div>")
      .addClass(`toast-message ${type}`)
      .text(text)
      .css({
        position: "fixed",
        top: "20px",
        right: "20px",
        background: colors[type].bg,
        color: "white",
        padding: "12px 20px",
        borderRadius: "8px",
        fontSize: "14px",
        fontWeight: "600",
        zIndex: "1001",
        boxShadow: `0 4px 12px ${colors[type].shadow}`,
        animation: "slideInSuccess 0.4s ease",
        maxWidth: "300px",
        wordWrap: "break-word",
      });

    $("body").append($message);

    // Remove message after 4 seconds
    setTimeout(() => {
      $message.css("animation", "slideOutSuccess 0.3s ease");
      setTimeout(() => $message.remove(), 300);
    }, 4000);
  }
}

// Initialize the Instagram profile (مثل قبل)
window.instagramProfile = new InstagramProfile();

/* ====== نمونه‌ی استفاده در بیرون کلاس ======
window.instagramProfile.setQRCode("Z-510");
window.instagramProfile.setLocation(37.2866561, 49.5692702);
window.instagramProfile.fetchProfileDataFromAPI();

function myOtherFunction() {
  const lat = window.instagramProfile.getLatitude();
  const lng = window.instagramProfile.getLongitude();
  const qr  = window.instagramProfile.getQRCode();
  console.log({lat, lng, qr});
}
*/
