$(document).ready(() => {
    // Declare $ variable
    const $ = window.jQuery;

    class InstagramProfile {
      constructor() {
        this.isFollowing = false;
        this.isOwnProfile = this.currentUserId === this.profileUserId;
        this.selectedMedia = [];

        this.profileData = this.loadProfileData();

        this.initializeElements();
        this.bindEvents();
        this.updateUI();
        this.loadProfileFromStorage();
        this.fetchProfileDataFromAPI();
      }

      fetchProfileDataFromAPI() {
        $.ajax({
          url: "http://127.0.0.1:8000/user/53", // 🔁 آدرس واقعی API پروفایل را اینجا قرار بده
          type: "GET",
          dataType: "json",
          beforeSend: function (xhr) {
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );

        },
          success: (response) => {
            console.log(response);
            const user = response.user;
            this.profileData.first_name = user.first_name;
            this.profileData.last_name = user.last_name;
            this.profileData.nationalcode = user.national_code;
            this.profileData.username = user.username;
            this.profileData.bio = user.bio;
            this.profileData.profile_pic = user.profile_pic;
            this.profileData.is_private = user.is_private;
            // console.log(this.profileData.is_private);
            // نمایش در UI
            this.profileData.fullName = `${user.first_name} ${user.last_name}`;
            this.profileData.profilePicture = user.profile_pic;

            this.saveProfileData();
            this.loadProfileFromStorage();
          },
          error: (xhr) => {
            this.showErrorMessage("خطا در دریافت اطلاعات پروفایل.");
            console.error(xhr.responseText);
          },
        });
      }

      initializeElements() {
        // Cache jQuery objects for better performance
        this.$profilePicture = $("#profilePicture");
        this.$fullName = $("#fullName");
        this.$profileBio = $("#profileBio");
        this.$buttonSection = $("#buttonSection");
        this.$profileToggle = $("#profileToggle");
        this.$followStatus = $("#followStatus");

        // Modal elements
        this.$editModal = $("#editModal");
        this.$closeModal = $("#closeModal");
        this.$editFullName = $("#editFullName");
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
        $(".current-picture").on("click", () =>
          this.$profilePictureInput.click()
        );

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
        this.$richTextButtons.on("click", (e) => {
          e.preventDefault();
          const command = $(e.currentTarget).data("command");
          document.execCommand(command, false, null);
          $(e.currentTarget).toggleClass("active");
          this.$postDescription.focus();
        });

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
          this.toggleFollow(); // وضعیت فالو رو تغییر می‌ده
          this.updateFollowButton(); // دکمه رو بروزرسانی می‌کنه
        });

        $("#newCustomerToggle").on("change", (e) => {
          const isChecked = e.target.checked;
          this.isPrivate = isChecked; // checked = عمومی → پس isPrivate = false

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

      loadProfileData() {
        const defaultData = {
          fullName: "اسم کامل",
          bio: "This is the bio section. Write something cool here!",
          profilePicture: "/placeholder.svg?height=90&width=90",
        };

        const savedData = localStorage.getItem("profileData");
        return savedData
          ? $.extend({}, defaultData, JSON.parse(savedData))
          : defaultData;
      }

      saveProfileData() {
        localStorage.setItem("profileData", JSON.stringify(this.profileData));
      }

      loadProfileFromStorage() {
        this.$fullName.text(this.profileData.fullName);
        this.$profileBio.text(this.profileData.bio);
        this.$profilePicture.attr("src", this.profileData.profilePicture);
      }

      toggleProfileView() {
        this.isOwnProfile = !this.isOwnProfile;
        if (this.isOwnProfile) {
          this.isFollowing = false;
        }
        this.updateUI();
      }

      updateUI() {
        // this.updateButtons();
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

     openEditModal() {
      // Use cached profile data instead of new AJAX request
      const profile = this.profileData;
      this.isPrivate = profile.is_private || false;

      $("#newCustomerToggle").prop("checked", !this.isPrivate ? 0 : 1, ) // اینجا تغییر دادیم);
      $(".order-toggle-label").text(this.isPrivate ? "خصوصی" : "عمومی");

      this.$editFullName.val(profile.fullName);
      this.$editBio.val(profile.bio);
      this.$previewPicture.attr("src", profile.profilePicture);

      this.$editModal.addClass("active");
      $("body").css("overflow", "hidden");
      setTimeout(() => this.$editFullName.focus(), 100);
    }

      closeEditModal() {
        this.$editModal.removeClass("active");
        $("body").css("overflow", "auto");
        this.$profilePictureInput.val("");
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

      saveProfileChanges() {
        const fullName = this.$editFullName.val().trim();
        const bio = this.$editBio.val().trim();
        const profilePic = this.$previewPicture.attr("src");

        if (!fullName) {
          this.showErrorMessage("لطفاً نام کامل را وارد کنید.");
          return;
        }

        // ساخت داده نهایی برای ارسال فقط با فیلدهای تغییر یافته
        const payload = {
          first_name: fullName.split(" ")[0],
          last_name: fullName.split(" ").slice(1).join(" "),
          bio: bio,
          profile_pic: profilePic,
          nationalcode: this.profileData.nationalcode,
         is_private: !this.isPrivate ? 0 : 1,  // اینجا تغییر دادیم
        };

        console.log("📤 داده‌ای که برای آپدیت پروفایل ارسال میشه:", payload);

        $.ajax({
          url: "/api/user/profile/update",
          method: "POST",
          contentType: "application/json",
          data: JSON.stringify(payload),
          success: () => {
            // به‌روزرسانی داده‌های لوکال
            this.profileData.fullName = `${payload.first_name} ${payload.last_name}`;
            this.profileData.bio = payload.bio;
            this.profileData.profilePicture = payload.profile_pic;
            this.saveProfileData();
            this.loadProfileFromStorage();

            this.showSuccessMessage("پروفایل با موفقیت بروزرسانی شد ");
            this.closeEditModal();
          },
          error: () => {
            this.showErrorMessage("خطا در ذخیره تغییرات پروفایل ");
          },
          complete: () => {
            this.$saveProfile
              .removeClass("loading")
              .text("ذخیره تغییرات")
              .prop("disabled", false);
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
            this.$fileInput.val(""); // Clear previous value
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
        const description = this.$postDescription.html().trim();

        if (!description) {
          this.showErrorMessage("لطفاً توضیحات را وارد کنید.");
          return;
        }

        const images = [];
        const videos = [];
        let filesProcessed = 0;

        const totalFiles = this.selectedMedia.length;
        if (totalFiles === 0) {
          this.showErrorMessage("حداقل یک فایل رسانه‌ای انتخاب کنید.");
          return;
        }

        const onAllFilesProcessed = () => {
          const postData = {
            description,
            images,
            videos,
          };

          console.log("📤 داده‌ای که برای ساخت پست ارسال میشه:", postData); // 🔍 لاگ گرفتن از شیء

          $.ajax({
            url: "/api/user/posts",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(postData),

            success: (response) => {
              this.showSuccessMessage("پست با موفقیت ارسال شد ");
              this.closeCreatePostModal();
            },
            error: (xhr) => {
              this.showErrorMessage("خطا در ارسال پست ");
            },
          });
        };

        this.selectedMedia.forEach((media) => {
          const reader = new FileReader();
          reader.onload = (e) => {
            if (media.type === "image") images.push(e.target.result);
            else if (media.type === "video") videos.push(e.target.result);

            filesProcessed++;
            if (filesProcessed === totalFiles) {
              onAllFilesProcessed();
            }
          };
          reader.onerror = () => {
            this.showErrorMessage("خطا در خواندن فایل.");
          };
          reader.readAsDataURL(media.file);
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

    // Initialize the Instagram profile
    window.instagramProfile = new InstagramProfile();
  });
