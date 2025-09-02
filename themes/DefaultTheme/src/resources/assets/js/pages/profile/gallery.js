// Dynamic API Configuration - Updated with correct endpoint
const URLpath = window.location.pathname.split("/")
const loadId = URLpath[URLpath.length - 1] // آخرین بخش URL (65)
const API_CONFIG = {
    baseUrl: "https://halvaa.net",
    endpoints: {
        posts: `/postof/${loadId}`,
        deletePost: "/user-posts/{id}",
        userPosts: "/postof/{userId}",
        updatePost: "/user-posts/{postId}/update", // Fixed endpoint
        addComment: "/user-posts/{postId}/comment",
        toggleLike: "/user-posts/{postId}/like",
        like_exist: "/user-posts/{postId}/like_exist",
    },
    headers: {
        "Content-Type": "application/json",
    },
    timeout: 10000,
}

// Helper function to build dynamic URLs
function buildApiUrl(endpoint, params = {}) {
    let url = API_CONFIG.baseUrl + API_CONFIG.endpoints[endpoint]

    // Replace placeholders with actual values
    Object.keys(params).forEach((key) => {
        url = url.replace(`{${key}}`, params[key])
    })

    return url
}
// ===== Shared file validation (same as Create Post) =====
/*const FILE_SIZE_LIMITS = {
  image: 2 * 1024 * 1024,   // 2MB
  video: 10 * 1024 * 1024,  // 10MB
};
*/
function isImageFile(file) { return file?.type?.startsWith("image/"); }
function isVideoFile(file) { return file?.type?.startsWith("video/"); }
function isSupportedMedia(file) { return isImageFile(file) || isVideoFile(file); }

// پیام‌ها دقیقاً مطابق کد ایجاد پست
function validateFileSizeExact(file) {
  if (isImageFile(file) && file.size > FILE_SIZE_LIMITS.image) {
    return { valid: false, error: `حجم عکس "${file.name}" نباید بیشتر از ۲ مگابایت باشد.` };
  }
  if (isVideoFile(file) && file.size > FILE_SIZE_LIMITS.video) {
    return { valid: false, error: `حجم ویدیو "${file.name}" نباید بیشتر از 10 مگابایت باشد.` };
  }
  return { valid: true };
}

// کلید یکتا برای تشخیص Duplicate مانند ایجاد پست (name + size)
function dupKey(file) {
  return `${file?.name || "unknown"}__${file?.size || 0}`;
}


function getCurrentUserId() {
    // Method 1: Extract from current URL path
    const pathMatch = window.location.pathname.match(/\/user\/profile\/(\d+)/)
    if (pathMatch) {
        return pathMatch[1]
    }

    // Method 2: Extract from data attribute or meta tag
    const userIdFromMeta = document.querySelector('meta[name="user-id"]')?.getAttribute("content")
    if (userIdFromMeta) {
        return userIdFromMeta
    }

    // Method 3: Extract from a global variable (if set by your backend)
    if (typeof window.currentUserId !== "undefined") {
        return window.currentUserId
    }

    // Method 4: Extract from localStorage or sessionStorage
    const storedUserId = localStorage.getItem("userId") || sessionStorage.getItem("userId")
    if (storedUserId) {
        return storedUserId
    }

    // Fallback: return default user ID
    return "4"
}

// FIXED: Helper function to determine media type from file extension or MIME type
function getMediaType(item) {
    // First check if type is explicitly provided
    if (item.type) {
        return item.type
    }

    // If no type, try to determine from file extension or MIME type
    if (item.image) {
        const src = item.image.toLowerCase()
        const videoExtensions = [".mp4", ".webm", ".ogg", ".avi", ".mov", ".wmv", ".flv", ".mkv"]
        const imageExtensions = [".jpg", ".jpeg", ".png", ".gif", ".bmp", ".webp", ".svg"]

        // Check video extensions
        if (videoExtensions.some((ext) => src.includes(ext))) {
            return "video"
        }

        // Check image extensions
        if (imageExtensions.some((ext) => src.includes(ext))) {
            return "image"
        }
    }

    // Default fallback
    return "image"
}

// Global variables - optimized
let posts = []
let currentPost = null
let isLiked = false
let modalSwiper = null
let loadingTimeout = null
let retryCount = 0
const MAX_RETRIES = 10
const CURRENT_USER_ID = getCurrentUserId()

// Cached DOM elements to avoid repeated queries
const $body = window.$(document.body)
const $document = window.$(document)

// File size limits (in bytes)
const FILE_SIZE_LIMITS = {
    image: 2 * 1024 * 1024, // 2MB
    video: 10 * 1024 * 1024, // 10MB
}

// Debounce utility function
function debounce(func, wait) {
    let timeout
    return function executedFunction(...args) {
        const later = () => {
            window.clearTimeout(timeout)
            func(...args)
        }
        window.clearTimeout(timeout)
        timeout = window.setTimeout(later, wait)
    }
}

// Helper function to get CSRF token
function getCSRFToken() {
    return (
        window.$('meta[name="csrf-token"]').attr("content") ||
        window.$('input[name="_token"]').val() ||
        window.$('meta[name="_token"]').attr("content")
    )
}

// FIXED: Helper function to set authentication headers (removed Content-Type for FormData)
function setAuthHeaders(xhr) {
    // Add CSRF token
    const csrfToken = getCSRFToken()
    if (csrfToken) {
        xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken)
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest")
    } else {
        console.warn("CSRF token not found. Make sure it's included in your page meta tags.")
    }
}

// Initialize event listeners
window.$(document).ready(() => {
    initializeApp()
})

function initializeApp() {
    loadPosts()
    initializeModalEvents()
    initializeGlobalErrorHandler()
    initializeLikeButtonEvents()
}

// FIXED: Initialize like button events properly
function initializeLikeButtonEvents() {
    // Remove any existing listeners first
    $(document).off("click", "#gallery-like-btn")

    // Add delegated event listener for like button
    $(document).on("click", "#gallery-like-btn", handleLikeClick)
}

function loadPosts(userId = null, retry = 0) {
    let url

    if (userId) {
        url = buildApiUrl("userPosts", { userId })
    } else {
        url = buildApiUrl("posts")
    }

    window.$.ajax({
        url,
        method: "GET",
        dataType: "json",
        timeout: API_CONFIG.timeout,
        beforeSend: (xhr) => {
            setAuthHeaders(xhr)
            showLoadingIndicator()
        },
        success: (response) => {
           // console.log("Posts loaded successfully:", response)
            handlePostsResponse(response)
            hideLoadingIndicator()

            // کنترل وضعیت checkWriter و مخفی‌سازی دکمه ویرایش
            const editBtn = document.getElementById("gallery-edit-post-btn")
            if (editBtn) {
                if (response.checkWriter === 0) {
                    editBtn.classList.add("d-none")
                } else {
                    editBtn.classList.remove("d-none")
                }
            }

            // بارگذاری لایک‌ها برای هر پست
            posts.forEach((post) => {
                loadPostLikes(post.id)
            })

            renderPostsGrid()
            initializePostGrid()
            retryCount = 0
        },
        error: (xhr, status, error) => {
            console.error("Error loading posts:", {
                xhr,
                status,
                error,
                retry,
            }) 
            hideLoadingIndicator()

            if (retry < MAX_RETRIES && (status === "timeout" || xhr.status >= 500)) {
                const nextRetry = retry + 1
                window.setTimeout(() => {
                    loadPosts(userId, nextRetry)
                }, Math.pow(2, nextRetry) * 1000)
                showRetryMessage(nextRetry)
            } else {
                handleAjaxError(xhr, status, error)
            }
        },
    })
}


function loadSinglePostAndOpenModal(postId, retry = 0) {
    const url = buildApiUrl("singlePost", { id: postId })

    window.$.ajax({
        url,
        method: "GET",
        dataType: "json",
        timeout: API_CONFIG.timeout,
        beforeSend: (xhr) => {
            setAuthHeaders(xhr)
            showLoadingIndicator()
        },
        success: (response) => {
            const transformedPost = transformPostData(response)
            currentPost = transformedPost

            const existingIndex = posts.findIndex((p) => p.id === transformedPost.id)
            if (existingIndex === -1) {
                posts.push(transformedPost)
            } else {
                posts[existingIndex] = transformedPost
            }

            // Open modal first
            openModalWithPost(transformedPost)
            hideLoadingIndicator()
            retryCount = 0

            // Then load like data to sync everything
            loadPostLikes(postId)
        },
        error: (xhr, status, error) => {
            console.error("Error loading single post:", { xhr, status, error, retry })
            hideLoadingIndicator()

            if (retry < MAX_RETRIES && (status === "timeout" || xhr.status >= 500)) {
                const nextRetry = retry + 1
                window.setTimeout(() => {
                    loadSinglePostAndOpenModal(postId, nextRetry)
                }, Math.pow(2, nextRetry) * 1000)
                showRetryMessage(nextRetry)
            } else {
                handleAjaxError(xhr, status, error)
            }
        },
    })
}

// FIXED: Enhanced loadPostLikes function that properly syncs with UI
function loadPostLikes(postId, callback) {
    const url = buildApiUrl("like_exist", { postId })
    return window.$.ajax({
        url,
        method: "GET",
        dataType: "json",
        timeout: API_CONFIG.timeout,
        beforeSend: (xhr) => setAuthHeaders(xhr),
    })
        .then((response) => {
            const { like_count: count, like_exist: liked } = response

            // Update the post in the posts array
            const post = posts.find((p) => p.id === postId)
            if (post) {
                post.like_count = count
                post.is_liked = liked
            }

            // Update grid UI
            updateLikeCountInGrid(postId, count)

            // If this is the current post in modal, update modal UI and global state
            if (currentPost && currentPost.id === postId) {
                currentPost.like_count = count
                currentPost.is_liked = liked
                isLiked = liked // FIXED: Sync global variable
                updateLikeUI(liked, count)
                updateLikesCount(count)
            }

            // Call callback if provided
            if (callback && typeof callback === "function") {
                callback(response)
            }

            return response
        })
        .catch((err) => {
            console.error("Error loading likes for post", postId, err)
            throw err
        })
}

// FIXED: Enhanced updateLikeUI function
function updateLikeUI(liked, count) {
    const btn = document.querySelector("#gallery-like-btn")
    if (!btn) return

    // Update button visual state
    btn.classList.toggle("liked", liked)

    // Update count in button if it has a counter
    const cnt = btn.querySelector(".like-count")
    if (cnt) cnt.textContent = count

    // Update the main gallery likes count display
    const galleryCnt = document.getElementById("gallery-likes-count-id")
    if (galleryCnt) {
        galleryCnt.textContent = `${count} پسندیدن`
    }

    // FIXED: Sync global variable
    isLiked = liked
}

// Function to load posts for current user specifically
function loadCurrentUserPosts() {
    loadPosts(CURRENT_USER_ID)
}

// Function to load posts for any specific user
function loadUserPosts(userId) {
    loadPosts(userId)
}

// Enhanced data transformation with validation
function handlePostsResponse(response) {
    try {
        if (response.post) {
            const transformed = transformPostData({
                ...response.post,
                like_count: response.like_count,
                like_users: response.like_users,
            })
            posts = transformed ? [transformed] : []
            renderPostsGrid()
            posts.forEach((post) => {
                loadPostLikes(post.id)
            })
        } else if (Array.isArray(response)) {
            posts = response.map(transformPostData).filter(Boolean)
        } else if (response.data && Array.isArray(response.data)) {
            posts = response.data.map(transformPostData).filter(Boolean)
        } else if (response.posts && Array.isArray(response.posts)) {
            posts = response.posts.map(transformPostData).filter(Boolean)
        } else {
            const singlePost = transformPostData(response)
            posts = singlePost ? [singlePost] : []
        }
    } catch (error) {
        console.error("Error processing posts response:", error)
        posts = []
        showErrorMessage("خطا در پردازش اطلاعات دریافتی")
    }
}

// FIXED: Enhanced transformPostData function with proper media type detection
function transformPostData(apiResponse) {
    try {
        const postData = apiResponse.post || apiResponse
        const gallery = postData.gallery || []

        const comments = (postData.post_comments || postData.comments || []).map((comment) => {
            const userObj = comment.user || {}
            const fullName = `${userObj.first_name || ""} ${userObj.last_name || ""}`.trim()
            const username = userObj.username || fullName || "ناشناس"

            return {
                user: {
                    username: username,
                    full_name: fullName,
                    profileImage: userObj.profile_image?.image || userObj.profile_photo_url || null,
                    displayName: userObj.username || fullName || "ناشناس",

                },
                text: comment.comment || comment.text || "",
            }
        })

        // FIXED: Extract user information properly from the API
        const userData = postData.user || {}
        const userFullName = `${userData.first_name || ""} ${userData.last_name || ""}`.trim()
        const userName = userData.username || userFullName || "کاربر ناشناس"
        const userProfileImage = userData.profile_image?.image || userData.profile_photo_url || null

        return {
            id: postData.id,
            user_id: postData.user_id,
            user_image: userProfileImage,
            user_name: userName, // ADDED: Store the user name
            user_full_name: userFullName, // ADDED: Store the full name
            slides: gallery
                .map((item) => ({
                    type: getMediaType(item), // FIXED: Use proper media type detection
                    src: item.image,
                    id: item.id,
                    ordering: item.ordering || 0,
                }))
                .filter((slide) => slide.src)
                .sort((a, b) => a.ordering - b.ordering),
            description: postData.description || "",
            like_count: apiResponse.like_count ?? postData.like_count ?? 0,
            comments: comments,
            is_liked: apiResponse.is_liked ?? apiResponse.like_exist ?? false,
            created_at: postData.created_at,
            updated_at: postData.updated_at,
        }
    } catch (error) {
        console.error("Error transforming post data:", error, apiResponse)
        return null
    }
}

// FIXED: Enhanced handleLikeClick function - Compatible with older jQuery
function handleLikeClick(e) {
    e.preventDefault()

    if (!currentPost || !currentPost.id) {
        console.error("No current post available")
        return
    }

    // Disable button during request
    const btn = e.target.closest("#gallery-like-btn")
    if (btn) {
        btn.disabled = true
    }

    toggleLike(currentPost.id)
        .then((response) => {
           // console.log("Like toggled successfully")
        })
        .catch((err) => {
            console.error(`Like failed [${err.status}]: ${err.message}`, err.details)
            alert(`خطا در لایک کردن (${err.status}): ${err.message}`)
        })
        .always(() => {
            // Re-enable button - using .always() instead of .finally()
            if (btn) {
                btn.disabled = false
            }
        })
}

// FIXED: Enhanced toggleLike function - Returns proper jQuery Promise
function toggleLike(postId) {
    const action = isLiked ? "unlike" : "like"

    return window.$.ajax({
        url: buildApiUrl("toggleLike", { postId }),
        method: "POST",
        dataType: "json",
        data: { post_id: postId, action },
        beforeSend: setAuthHeaders,
        timeout: API_CONFIG.timeout,
    })
        .then(() => {
            // After successful toggle, reload the like data to sync everything
            return loadPostLikes(postId)
        })
        .fail((xhrErr) => {
            let message = xhrErr.statusText || xhrErr.responseText || "خطای نامشخص"
            const status = xhrErr.status || 0

            try {
                if (xhrErr.responseText) {
                    const json = JSON.parse(xhrErr.responseText)
                    message = json.message || json.error || message
                }
            } catch (parseError) {
                // Keep the original message if JSON parsing fails
            }

            // Create a rejected promise with consistent error format
            const error = { status: status, message: message }
            return window.$.Deferred().reject(error).promise()
        })
}

// FIXED: Enhanced updateLikeButton function
function updateLikeButton() {
    const btn = document.querySelector("#gallery-like-btn")
    if (btn) {
        btn.classList.toggle("liked", isLiked)
    }
}

// FIXED: Enhanced updateLikesCount function
function updateLikesCount(count) {
    // Use the provided count or get from currentPost
    const likeCount = count !== undefined ? count : currentPost ? currentPost.like_count : 0

    const countElement = document.getElementById("gallery-likes-count-id")
    if (countElement) {
        countElement.textContent = `${likeCount} پسندیدن`
    }
}

// ۱) Delegated event برای submit فرم کامنت و input تگ
window
    .$(document)
    // وقتی هر جایی در داکیومنت، فرمی با این آی‌دی سابمیت شد
    .on("submit", "#gallery-comment-form-id", (e) => {
        e.preventDefault()
        addComment()
    })
    // روی input کامنت گوش می‌دیم برای فعال/غیرفعال کردن دکمه
    .on("input", "#gallery-comment-input-id", function () {
        const hasText = window.$(this).val().trim().length > 0
        window.$("#gallery-submit-btn").prop("disabled", !hasText)
    })

function addComment() {
    const $input = window.$("#gallery-comment-input-id")
    const text = $input.val().trim()
    // اگر ورودی خالیه یا currentPost مشکل داره، بیرون می‌زنیم
    if (!text || !currentPost?.id) return

    const $btn = window.$("#gallery-submit-btn")
    $btn.prop("disabled", true).text("ارسال...")

    const url = `/user-posts/${currentPost.id}/comment`
    const payload = { comment: text }

    window.$.ajax({
        url,
        method: "POST",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify(payload),
        beforeSend: (xhr) => setAuthHeaders(xhr),
        success: (response) => {
            // واکشی داده‌های کامنت و اطلاعات کاربر از پاسخ API
            const data = response.data || {}
            const commentData = Object.assign({}, data, {
                id: data.id || data.comment_id || data.post_id,
                comment: data.comment || response.comment,
            })
            const displayName = response.username || commentData.username || commentData.user?.name || "شما"
            const profileImage = response.profile_image || commentData.profile_image || commentData.user?.profile_image

            // ساخت آواتار: تصویر یا آیکون پیش‌فرض
            const avatarHtml = profileImage
                ? `<img src="${profileImage}" alt="${displayName}" class="img-fluid rounded-circle" />`
                : `<i class="ri-user-line"></i>`

            // ساخت HTML کامنت جدید
            const commentHtml = `
        <div class="gallery-comment" data-comment-id="${commentData.id}">
          <div class="gallery-comment-avatar d-flex align-items-center justify-content-center overflow-hidden">
            ${avatarHtml}
          </div>
          <div class="gallery-comment-content">
            <div class="gallery-comment-text">
              <span class="gallery-comment-username">${displayName}</span>
              <p>${commentData.comment}</p>
            </div>
          </div>
        </div>
      `

            const $list = window.$("#gallery-overlay-content-id")
            $list.append(commentHtml)

            // اسکرول به پایین
            $list.scrollTop($list.prop("scrollHeight"))

            // به‌روز‌رسانی آرایه کامنت‌ها در مدل محلی
            currentPost.comments = currentPost.comments || []
            currentPost.comments.push({
                user: { username: displayName, profileImage: profileImage },
                text: commentData.comment,
            })

            // به‌روز‌رسانی شمارش نظرات در شبکه
            const commentBtn = document.querySelector(
                `.gallery-post-item[data-post=\"${currentPost.id}\"] .gallery-overlay-stat.comment-btn span`,
            )
            if (commentBtn) {
                commentBtn.textContent = currentPost.comments.length
            }

            // ریست فرم
            $input.val("").trigger("input")
            $btn.prop("disabled", true).text("ارسال")
        },
        error: (xhr, status, err) => {
            console.error("Error adding comment:", err)
            $btn.prop("disabled", false).text("ارسال")
            handleAjaxError(xhr, status, err)
        },
    })
}

// FIXED: Updated save function with correct endpoint and data structure (removed PUT method override)
function savePostChanges(postId, formData) {
    // Use the correct endpoint for updating posts
    const url = buildApiUrl("updatePost", { postId: postId })

   // console.log("Sending FormData to:", url)
    //console.log("FormData entries:")
   /* for (const [key, value] of formData.entries()) {
        console.log(key, value)
    } */

    return window.$.ajax({
        url: url,
        method: "POST", // Using POST method as required by your API
        data: formData, // Send FormData directly
        processData: false, // Don't process the data
        contentType: false, // Don't set content type (let browser set it with boundary)
        timeout: API_CONFIG.timeout,
        beforeSend: (xhr) => {
            // Only set CSRF token, not Content-Type
            setAuthHeaders(xhr)
            showLoadingIndicator()
        },
        success: (response) => {
            hideLoadingIndicator()
            // console.log("Post updated successfully:", response)

            // Handle the different response structure
            // Response is: { message: "...", post: {...} }
            // We need to transform it to match our expected structure
            if (response.post) {
                const updatedPost = transformPostData({
                    ...response.post,
                    // Add any additional data that might be missing
                    like_count: response.post.likes || 0,
                    like_users: response.post.like_users || [],
                    is_liked: response.post.is_liked || false,
                })

                if (updatedPost) {
                    // Update the posts array
                    const index = posts.findIndex((p) => p.id === updatedPost.id)
                    if (index !== -1) {
                        posts[index] = updatedPost
                    }

                    // Update current post
                    if (currentPost && currentPost.id === updatedPost.id) {
                        currentPost = updatedPost
                        updateModalContent()
                    }

                    renderPostsGrid()
                }
            }

            // Show success message
            showSuccessMessage(response.message || "پست با موفقیت به‌روزرسانی شد")
            closeModal()
        },
       error: (xhr, status, error) => {
    hideLoadingIndicator()
    console.error("Error updating post:", {
        status: xhr.status,
        statusText: xhr.statusText,
        responseText: xhr.responseText,
        error: error,
    })

    // نمایش پیام خطا
    alert(`ذخیره‌سازی پست با مشکل مواجه شد. لطفاً دوباره تلاش کنید.
موارد احتمالی
تعداد بیشتر از 10 آیتم
حجم بیشتر از 2مگابایت برای عکس و8 مگابایت برای ویدیو`)

    handleAjaxError(xhr, status, error)
},

    })
}

// Updated delete function with dynamic URL
function deletePost(postId) {
    const url = buildApiUrl("deletePost", { id: postId })

    return window.$.ajax({
        url: url,
        method: "DELETE",
        dataType: "json",
        timeout: API_CONFIG.timeout,
        beforeSend: (xhr) => {
            setAuthHeaders(xhr)
            showLoadingIndicator()
        },
        success: (response) => {
            hideLoadingIndicator()
          //  console.log("Post deleted successfully:", response)
            posts = posts.filter((p) => p.id !== postId)
            renderPostsGrid()
            closeModal()
        },
        error: (xhr, status, error) => {
            hideLoadingIndicator()
            handleAjaxError(xhr, status, error)
        },
    })
}

// FIXED: Enhanced updateLikeCountInGrid function
function updateLikeCountInGrid(postId, count) {
    const item = document.querySelector(`.gallery-post-item[data-post="${postId}"]`)
    if (!item) return
    const span = item.querySelector(".gallery-overlay-stat.like .like-count")
    if (span) span.textContent = count
}

// FIXED: Enhanced renderPostsGrid function with proper video rendering
function renderPostsGrid() {
    const $postsGrid = window.$("#gallery-posts-grid")
    $postsGrid.off("click", ".comment-btn").on("click", ".comment-btn", function () {
        const postId = window.$(this).closest(".gallery-post-item").data("post")
        loadSinglePostAndOpenModal(postId)
        showComments()
    })

    if ($postsGrid.length === 0) return

    const fragment = document.createDocumentFragment()
    posts.forEach((post, index) => {
        if (!post.slides?.length) return
        const firstSlide = post.slides[0]
        const slideCount = post.slides.length
        const postItem = document.createElement("div")
        postItem.className = "gallery-post-item"
        postItem.setAttribute("data-post", post.id)

        // FIXED: Proper video rendering based on media type
        let mediaElement = ""
        if (firstSlide.type === "video") {
            mediaElement = `<video src="${firstSlide.src}" preload="metadata" muted loop onerror="this.style.display='none'">
        <source src="${firstSlide.src}" type="video/mp4">
        Your browser does not support the video tag.
      </video>`
        } else {
            mediaElement = `<img src="${firstSlide.src}" alt="Post ${index + 1}" loading="lazy" onerror="this.style.display='none'" />`
        }

        postItem.innerHTML = `
      ${mediaElement}
      <div class="gallery-post-overlay">
        <div class="gallery-overlay-stat like">
          <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
          <span class="like-count">${post.like_count || 0}</span>
        </div>
        <div class="gallery-overlay-stat comment-btn">
          <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
          <span>${post.comments.length}</span>
        </div>
      </div>
      ${slideCount > 1 ? `<div class="gallery-slide-indicator">1/${slideCount}</div>` : ""}
    `

        fragment.appendChild(postItem)
    })

    $postsGrid.empty().append(fragment)

    // Load likes for each post after rendering
    posts.forEach((post) => {
        loadPostLikes(post.id)
    })
}

// Enhanced loading and error handling
function showLoadingIndicator() {
    window.clearTimeout(loadingTimeout)

    if (window.$("#gallery-loading-indicator").length === 0) {
        $body.append(`
      <div id="gallery-loading-indicator" class="gallery-loading-overlay">
        <div class="gallery-spinner"></div>
        <div class="gallery-loading-text">در حال بارگذاری...</div>
      </div>
    `)
    }

    window.$("#gallery-loading-indicator").show()

    loadingTimeout = window.setTimeout(() => {
        hideLoadingIndicator()
        showErrorMessage("زمان بارگذاری به پایان رسید. لطفاً دوباره تلاش کنید.")
    }, 30000)
}

function hideLoadingIndicator() {
    window.clearTimeout(loadingTimeout)
    window.$("#gallery-loading-indicator").hide()
}

function showRetryMessage(retryCount) {
    const $indicator = window.$("#gallery-loading-indicator .gallery-loading-text")
    if ($indicator.length > 0) {
        $indicator.text(`تلاش مجدد ${retryCount}/${MAX_RETRIES}...`)
    }
}

// FIXED: Enhanced error handling for better debugging (added 405 Method Not Allowed)
function handleAjaxError(xhr, status, error) {
    let errorMessage = "خطا در بارگذاری اطلاعات"
    let canRetry = false

    // Log detailed error information for debugging
    console.error("AJAX Error Details:", {
        status: xhr.status,
        statusText: xhr.statusText,
        responseText: xhr.responseText,
        error: error,
        url: xhr.responseURL || "Unknown URL",
    })

    switch (xhr.status) {
        case 0:
            errorMessage = "خطا در اتصال به سرور. لطفاً اتصال اینترنت خود را بررسی کنید."
            canRetry = true
            break
        case 400:
            errorMessage = "درخواست نامعتبر. لطفاً اطلاعات را بررسی کنید."
            // Try to get specific error message from response
            try {
                const response = JSON.parse(xhr.responseText)
                if (response.message) {
                    errorMessage = response.message
                }
            } catch (e) {
                // Keep default message
            }
            break
        case 401:
            errorMessage = "دسترسی غیرمجاز. لطفاً صفحه را رفرش کنید و دوباره تلاش کنید."
            break
        case 403:
            errorMessage = "دسترسی ممنوع. ممکن است نشست شما منقضی شده باشد."
            break
        case 404:
            errorMessage = "اطلاعات مورد نظر یافت نشد"
            break
        case 405:
            errorMessage = "متد HTTP مجاز نیست. API فقط از POST پشتیبانی می‌کند."
            break
        case 419:
            errorMessage = "نشست منقضی شده. لطفاً صفحه را رفرش کنید."
            canRetry = true
            break
        case 422:
            errorMessage = "اطلاعات ارسالی معتبر نیست"
            // Try to get validation errors
            try {
                const response = JSON.parse(xhr.responseText)
                if (response.errors) {
                    const errors = Object.values(response.errors).flat()
                    errorMessage = errors.join(", ")
                } else if (response.message) {
                    errorMessage = response.message
                }
            } catch (e) {
                // Keep default message
            }
            break
        case 429:
            errorMessage = "تعداد درخواست‌ها بیش از حد مجاز. لطفاً کمی صبر کنید."
            canRetry = true
            break
        case 500:
        case 502:
        case 503:
        case 504:
            errorMessage = "خطای سرور. لطفاً دوباره تلاش کنید."
            canRetry = true
            break
        default:
            if (status === "timeout") {
                errorMessage = "زمان درخواست به پایان رسید"
                canRetry = true
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message
            }
    }

    showErrorMessage(errorMessage, canRetry)
}

function showErrorMessage(message, canRetry = false) {
    const retryButton = canRetry
        ? `<button onclick="retryLastAction()" class="gallery-error-retry-btn">تلاش مجدد</button>`
        : ""

    const errorHtml = `
    <div id="gallery-error-message" class="gallery-error-message">
      <div class="gallery-error-content">
        <div class="gallery-error-icon">⚠️</div>
        <p>${message}</p>
        <div class="gallery-error-actions">
          ${retryButton}
          <button onclick="hideErrorMessage()" class="gallery-error-close-btn">بستن</button>
        </div>
      </div>
    </div>
  `

    window.$("#gallery-error-message").remove()
    $body.append(errorHtml)
    window.$("#gallery-error-message").show()

    window.setTimeout(() => {
        hideErrorMessage()
    }, 10000)
}

function hideErrorMessage() {
    window.$("#gallery-error-message").remove()
}

function refreshCSRFToken() {
    return window.$.ajax({
        url: "/csrf-token",
        method: "GET",
        success: (response) => {
            if (response.token) {
                window.$('meta[name="csrf-token"]').attr("content", response.token)
                //console.log("CSRF token refreshed")
            }
        },
        error: (xhr, status, error) => {
            console.warn("Failed to refresh CSRF token:", error)
        },
    })
}

function retryLastAction() {
    hideErrorMessage()

    const lastError = window.$("#gallery-error-message").data("lastError")
    if (lastError && (lastError.status === 419 || lastError.status === 401)) {
        refreshCSRFToken()
            .then(() => {
                loadPosts()
            })
            .catch(() => {
                loadPosts()
            })
    } else {
        loadPosts()
    }
}

function initializePostGrid() {
    window
        .$("#gallery-posts-grid")
        .off("click", ".gallery-post-item")
        .on("click", ".gallery-post-item", function (e) {
            e.preventDefault()
            const postId = Number.parseInt(window.$(this).data("post"))
            if (!postId) return

            const existingPost = posts.find((p) => p.id === postId)
            if (existingPost) {
                openModal(postId)
            } else {
                loadSinglePostAndOpenModal(postId)
            }
        })
}

// ADDED: Function to update user profile information in modal
function updateUserProfile() {
    if (!currentPost) return

    // Update user avatar - handle div containing img tag
    const avatarContainer = document.querySelector(".gallery-user-avatar")
    if (avatarContainer) {
        // Find the img tag inside the avatar container
        const avatarImg = avatarContainer.querySelector("img")

        if (avatarImg) {
            if (currentPost.user_image) {
                // Check if it's a full URL or relative path
                let imageUrl = currentPost.user_image
                if (!imageUrl.startsWith("http")) {
                    // If it's a relative path, prepend the base URL
                    imageUrl = `${API_CONFIG.baseUrl}/${imageUrl.replace(/^\/+/, "")}`
                }

                avatarImg.src = imageUrl
                avatarImg.alt = currentPost.user_name || "User Avatar"
            } else {
                // Use default avatar
                const defaultAvatar = `${API_CONFIG.baseUrl}/back/app-assets/images/portrait/small/default.jpg`
                avatarImg.src = defaultAvatar
                avatarImg.alt = currentPost.user_name || "User Avatar"
            }
        }
    }

    // Update username
    const usernameElement = document.querySelector(".gallery-username")
    if (usernameElement) {
        usernameElement.textContent = currentPost.user_name || "کاربر ناشناس"
    }
}

// FIXED: Enhanced updateModalContent function
function updateModalContent() {
    if (!currentPost) return

    window.$("#gallery-modal-description-id").text(currentPost.description)
    updateLikesCount(currentPost.like_count)
    updateLikeButton()
    updateUserProfile() // ADDED: Update user profile information
}

// FIXED: Enhanced openModalWithPost function
function openModalWithPost(post) {
    if (!post || !post.slides || post.slides.length === 0) {
        showErrorMessage("پست معتبر نیست")
        return
    }

    currentPost = post

    // FIXED: Initialize like state from post data
    isLiked = post.is_liked || false

    updateModalContent()
    createSwiperSlides()
    initializeModalVideoSettings()

    window.setTimeout(() => {
        initializeSwiper()
    }, 100)

    window.$("#gallery-post-modal").addClass("active")
    $body.css("overflow", "hidden")

    // FIXED: Load fresh like data after modal opens
    if (post.id) {
        loadPostLikes(post.id)
    }
}

function openModal(postId) {
    const post = posts.find((p) => p.id === postId)
    if (post) {
        openModalWithPost(post)
    } else {
        loadSinglePostAndOpenModal(postId)
    }
}

function closeModal() {
    if (modalSwiper) {
        try {
            modalSwiper.destroy(true, true)
        } catch (error) {
            console.warn("Error destroying Swiper:", error)
        }
        modalSwiper = null
    }

    window.$("#gallery-post-modal video").each(function () {
        try {
            this.pause()
            this.currentTime = 0 // Reset playback position
            this.removeAttribute("src") // Remove video source
            this.load() // Reset video element
        } catch (error) {
            console.warn("Error resetting video:", error)
        }
    })

    window.$("#gallery-post-modal").removeClass("active")
    $body.css("overflow", "")

    currentPost = null
    hideOverlay()
}

function pauseAllVideos() {
    window.$(".gallery-modal-swiper video").each(function () {
        try {
            this.pause()
        } catch (error) {
           console.warn("Error pausing video:", error)
        }
    })
}

const debouncedToggleSubmitButton = debounce(toggleSubmitButton, 300)

function initializeModalEvents() {
    window.$("#gallery-post-modal").on("click", function (e) {
        if (e.target === this) {
            closeModal()
        }
    })

    $document.on("keydown", (e) => {
        if (window.$("#gallery-post-modal").hasClass("active")) {
            if (e.key === "Escape") {
                closeModal()
            }
        }
    })
}

function initializeGlobalErrorHandler() {
    window.addEventListener("error", (e) => {
        console.error("Global error:", e.error)
    })

    window.addEventListener("unhandledrejection", (e) => {
        console.error("Unhandled promise rejection:", e.reason)
        e.preventDefault()
    })
}

function refreshPosts() {
    posts = []
    retryCount = 0
    loadPosts()
}

// Updated showComments to use currentPost.comments and display comment user.profileImage
function showComments() {
    const $overlay = window.$("#gallery-sliding-overlay-id")
    const $overlayTitle = window.$("#gallery-overlay-title-id")
    const $overlayContent = window.$("#gallery-overlay-content-id")
    const $commentForm = window.$("#gallery-comment-form-id")

    if ($overlay.length === 0 || $overlayTitle.length === 0 || $overlayContent.length === 0 || $commentForm.length === 0)
        return

    $overlayTitle.text("نظرات")
    $commentForm.show()

    const comments = Array.isArray(currentPost.comments) ? currentPost.comments : []

    const commentsHtml = comments
        .map(({ user, text }) => {
            // Determine avatar URL
            let avatarUrl
            if (user.profileImage) {
                if (/^https?:\/\//i.test(user.profileImage)) {
                    avatarUrl = user.profileImage
                } else {
                    const cleanPath = user.profileImage.replace(/^\/+/, "")
                    avatarUrl = `${window.location.origin}/${cleanPath}`
                }
            } else {
                avatarUrl = `${window.location.origin}/back/app-assets/images/portrait/small/default.jpg`
            }

            // Determine display name
            const displayName = user.username?.trim()
                ? user.username
                : `${user.firstName || ''} ${user.lastName || ''}`.trim()

            return `
        <div class="gallery-comment">
          <div class="gallery-comment-avatar d-flex align-items-center justify-content-center overflow-hidden">
            <img src="${avatarUrl}" alt="${displayName}" class="w-100 h-100 object-fit-cover" />
          </div>
          <div class="gallery-comment-content">
            <div class="gallery-comment-text">
              <span class="gallery-comment-username">${displayName}</span>
              ${text}
            </div>
          </div>
        </div>
      `
        })
        .join("")

    $overlayContent.html(`<div class="gallery-comments-list">${commentsHtml}</div>`)
    $overlay.addClass("active").removeClass("gallery-highoverlay")
}


function hideOverlay() {
    window.$("#gallery-sliding-overlay-id").removeClass("active").removeClass("gallery-highoverlay")
}

function toggleSubmitButton() {
    const $input = window.$("#gallery-comment-input-id")
    const $submitBtn = window.$("#gallery-submit-btn")

    if ($input.length > 0 && $submitBtn.length > 0) {
        $submitBtn.toggleClass("active", $input.val().trim().length > 0)
    }
}

function initializeModalVideoSettings() {
    window.$("#gallery-post-modal video").each(function () {
        const video = this
        const $video = window.$(this)

        try {
            $video.removeAttr("controls")
            video.autoplay = true
            video.muted = true
            video.loop = true
            video.playsInline = true

            $video.on("error", function () {
                console.warn("Video failed to load:", video.src)
                window.$(this).closest(".swiper-slide").append('<div class="video-error">خطا در بارگذاری ویدیو</div>')
            })

            $video.on("canplay", () => {
                video.play().catch((e) => console.log("Video autoplay failed:", e))
            })

            $video.on("contextmenu", (e) => e.preventDefault())
        } catch (error) {
            console.warn("Error setting up video:", error)
        }
    })
}

function showedit() {
    if (!currentPost) {
        showErrorMessage("پست برای ویرایش یافت نشد");
        return;
    }

    const $overlay = $("#gallery-sliding-overlay-id");
    const $overlayTitle = $("#gallery-overlay-title-id");
    const $overlayContent = $("#gallery-overlay-content-id");

    if ($overlay.length === 0 || $overlayTitle.length === 0 || $overlayContent.length === 0) return;

    // Title
    $overlayTitle.text("ویرایش پست");

    // Render form with <textarea> for description
    $overlayContent.html(`
    <div class="gallery-modal-body gallery-create-post-modal-body">
      <form id="gallery-post-form" class="gallery-create-post-modal-postform">
        <div class="gallery-form-group gallery-create-post-modal-form-group">
          <label for="gallery-post-description-id" class="gallery-form-label">
            توضیحات پست
          </label>
          <textarea id="gallery-post-description-id" class="gallery-form-control gallery-text-area" placeholder="توضیحات خود را اینجا بنویسید...">${currentPost.description || ''}</textarea>
        </div>

        <div class="gallery-form-group gallery-create-post-modal-form-group">
          <label class="gallery-form-label">رسانه‌های پست</label>
          <div id="gallery-media-preview-container-id" class="gallery-media-preview-container"></div>
          <div class="gallery-upload-area" id="gallery-upload-area-id">
            <div class="gallery-upload-content">
              <i class="ri-upload-cloud-fill fa-3x"></i>
              <h5 class="gallery-text-muted">فایل‌های جدید را اینجا بکشید</h5>
              <p class="gallery-text-muted gallery-mb-3">یا کلیک کنید تا از کامپیوتر خود انتخاب کنید</p>
              <button type="button" class="gallery-btn gallery-btn-outline-primary" id="gallery-select-media-btn">
                <span class="select_file_icon"><i class="ri-folder-6-fill"></i></span> انتخاب فایل‌های جدید
              </button>
              <p class="gallery-text-muted gallery-mt-2 gallery-small">
                فرمت‌های پشتیبانی شده: JPG، PNG، GIF، MP4، WebM، MOV<br>
                <strong>حداکثر اندازه:</strong> تصاویر 2MB، ویدیوها 10MB
              </p>
            </div>
          </div>
          <input type="file" id="gallery-file-input-id" multiple accept="image/*,video/*" style="display: none;">
          <div class="gallery-media-count" id="gallery-media-count-id"></div>
          <div id="gallery-upload-errors-id" class="gallery-upload-errors" style="display: none;"></div>
        </div>

        <div class="gallery-form-group gallery-create-post-modal-form-group-2">
          <button type="button" class="gallery-btn gallery-btn-primary gallery-btn-lg" id="gallery-delete-post-btn" style="background: #dc3545; border-color: #dc3545;">
            حذف پست
          </button>
          <button type="submit" class="gallery-btn gallery-btn-primary gallery-btn-lg">
            ذخیره تغییرات
          </button>
        </div>
      </form>
    </div>
  `);

    // Hide comments overlay if visible
    $("#gallery-comment-form-id").hide();

    // Show overlay
    $overlay.addClass("active gallery-highoverlay");
    initializeEditForm();
}

function initializeEditForm() {
    try {
        const $descriptionEditor = window.$("#gallery-post-description-id")
        if ($descriptionEditor.length > 0) {
            $descriptionEditor.html(currentPost.description)
        }

        displayCurrentMedia()
        initializeRichTextToolbar()
        initializeFileUpload()
        initializeFormSubmission()
        initializeDeleteFunctionality()
    } catch (error) {
        console.error("Error initializing edit form:", error)
        showErrorMessage("خطا در بارگذاری فرم ویرایش")
    }
}

// FIXED: Enhanced displayCurrentMedia function with proper video rendering
function displayCurrentMedia() {
    const $mediaPreview = $("#gallery-media-preview-container-id")
    const $mediaCount = $("#gallery-media-count-id")

    if ($mediaPreview.length === 0) return

    $mediaPreview.empty()

    currentPost.slides.forEach((slide, index) => {
        const $wrapper = $("<div>", {
            class: "media-preview",
            "data-index": index,
        })

        // FIXED: Determine media element based on slide type
        let $mediaEl
        if (slide.type === "video") {
            $mediaEl = $("<video>", {
                src: slide.src,
                controls: false,
                muted: true,
                preload: "metadata",
            })
        } else {
            $mediaEl = $("<img>", {
                src: slide.src,
            })
        }

        const $deleteBtn = $(`
      <button type="button" class="media-delete-btn" title="حذف این رسانه">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="15" y1="9" x2="9" y2="15"></line>
          <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
      </button>
    `)

        $deleteBtn.on("click", (e) => {
            e.preventDefault()
            e.stopPropagation()
            removeMediaFromPost(index)
        })

        const $typeBadge = $("<div>", {
            class: "media-type-badge",
            text: slide.type === "image" ? "IMG" : "VID",
        })

        const $overlay = $("<div>", { class: "media-overlay" })

        $wrapper.append($mediaEl, $overlay, $deleteBtn, $typeBadge)
        $mediaPreview.append($wrapper)
    })

    if ($mediaCount.length) {
        $mediaCount.text(`${currentPost.slides.length} فایل رسانه‌ای`)
        $mediaCount.css("display", currentPost.slides.length > 0 ? "block" : "none")
    }

    addMediaPreviewStyles()
}

function addMediaPreviewStyles() {
    if ($("#media-preview-styles").length) return

    const styleContent = `
    .media-preview {
      position: relative;
      overflow: hidden;
      border-radius: 8px;
      transition: all 0.3s ease;
      width:120px;
      height:120px;
      overflow:hidden;
    }

    .media-preview:hover {
      transform: scale(1.02);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .media-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.3);
      opacity: 0;
      transition: opacity 0.3s ease;
      pointer-events: none;
    }

    .media-preview:hover .media-overlay {
      opacity: 1;
    }

    .media-delete-btn {
      position: absolute;
      top: 8px;
      right: 8px;
      background: rgba(220, 53, 69, 0.9);
      border: none;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      opacity: 0;
      transform: scale(0.8);
      transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      backdrop-filter: blur(10px);
      z-index: 10;
    }

    .media-delete-btn:hover {
      background: rgba(220, 53, 69, 1);
      transform: scale(1.1);
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }

    .media-preview:hover .media-delete-btn {
      opacity: 1;
      transform: scale(1);
    }

    .media-delete-btn svg {
      width: 18px;
      height: 18px;
      color: white;
      filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
    }

    .media-type-badge {
      position: absolute;
      top: 8px;
      left: 8px;
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 10px;
      font-weight: bold;
      text-transform: uppercase;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .media-preview img,
    .media-preview video {
      transition: transform 0.3s ease;
      object-fit:cover;
      width:100%;
      height:100%;
    }

    .media-preview:hover img,
    .media-preview:hover video {
      transform: scale(1.05);
    }

    .media-preview.deleting {
      animation: deleteAnimation 0.5s ease-out forwards;
    }

    @keyframes deleteAnimation {
      0% {
        opacity: 1;
        transform: scale(1);
      }
      50% {
        opacity: 0.5;
        transform: scale(0.9);
      }
      100% {
        opacity: 0;
        transform: scale(0.8);
        height: 0;
        margin: 0;
        padding: 0;
      }
    }

    @media (max-width: 768px) {
      .media-delete-btn {
        width: 28px;
        height: 28px;
        top: 6px;
        right: 6px;
      }

      .media-delete-btn svg {
        width: 16px;
        height: 16px;
      }

      .media-type-badge {
        top: 6px;
        left: 6px;
        padding: 2px 6px;
        font-size: 9px;
      }
    }
  `

    $("<style>", {
        id: "media-preview-styles",
        text: styleContent,
    }).appendTo("head")
}

function initializeRichTextToolbar() {
    const $toolbar = $(".rich-text-toolbar")
    if ($toolbar.length === 0) return

    $toolbar.on("click", "button", function (e) {
        e.preventDefault()
        const command = $(this).data("command")
        if (command) {
            document.execCommand(command, false, null)
            $(this).toggleClass("active")
        }
    })
}

function initializeFileUpload() {
    const $fileInput = $("#gallery-file-input-id")
    const $selectBtn = $("#gallery-select-media-btn")
    const $uploadArea = $("#gallery-upload-area-id")

    if ($selectBtn.length && $fileInput.length) {
        $selectBtn.on("click", () => {
            $fileInput.click()
        })
    }

    if ($fileInput.length) {
        $fileInput.on("change", handleFileSelection)
    }

    if ($uploadArea.length) {
        $uploadArea.on("dragover", (e) => {
            e.preventDefault()
            $uploadArea.addClass("dragover")
        })

        $uploadArea.on("dragleave", () => {
            $uploadArea.removeClass("dragover")
        })

        $uploadArea.on("drop", (e) => {
            e.preventDefault()
            $uploadArea.removeClass("dragover")
            const files = Array.from(e.originalEvent.dataTransfer.files)
            addNewMediaFiles(files)
        })
    }
}

function initializeFormSubmission() {
  const $form = $("#gallery-post-form");
  if ($form.length === 0) return;

  $form.on("submit", async function (e) {
    e.preventDefault();

    if (!currentPost || !currentPost.id) {
      showErrorMessage("خطا: اطلاعات پست یافت نشد");
      return;
    }

    // 1) توضیحات
    const description = $("#gallery-post-description-id").val().trim();
    if (!description) {
      showErrorMessage("لطفاً توضیحات را وارد کنید.");
      return;
    }

    // 2) ساخت FormData
    const formData = new FormData();
    formData.append("post_id", currentPost.id);
    formData.append("description", description);

    // 3) غیرفعال کردن دکمه
    const $submitBtn = $form.find('button[type="submit"]');
    const originalText = $submitBtn.text();
    $submitBtn.prop("disabled", true).text("در حال ذخیره...");

    // 4) گردآوری همه‌ی فایل‌ها (قدیمی + جدید)
    const allFiles = [];
    for (const slide of currentPost.slides) {
      if (slide.file instanceof File) {
        // فایل‌های جدیدی که کاربر الان اضافه کرده
        allFiles.push({ file: slide.file, type: slide.type });
      } else if (slide.src) {
        // رسانه‌های موجود را به File تبدیل می‌کنیم تا ولیدیشن رویشان اعمال شود
        const filename = slide.src.split("/").pop();
        const mimeType = (slide.type === "video" ? "video/mp4" : "image/jpeg");
        try {
          const file = await urlToFile(slide.src, filename, mimeType);
          allFiles.push({ file, type: slide.type });
        } catch (err) {
          console.error("Error converting existing media:", err);
        }
      }
    }

    if (allFiles.length === 0) {
      showErrorMessage("پست باید حداقل یک رسانه داشته باشد");
      $submitBtn.prop("disabled", false).text(originalText);
      return;
    }

    // 5) ــ ولیدیشن نوع فایل (مانند ایجاد پست)
    const unsupported = allFiles
      .filter(({ file }) => !isSupportedMedia(file))
      .map(({ file }) => ({
        file,
        error: `نوع فایل پشتیبانی نمی‌شود: ${file?.name || "فایل ناشناس"}`
      }));

    // 6) ــ ولیدیشن حجم دقیقاً با همان پیام‌ها
    const oversize = allFiles
      .map(({ file }) => ({ file, v: validateFileSizeExact(file) }))
      .filter(({ v }) => !v.valid)
      .map(({ file, v }) => ({ file, error: v.error }));

    // 7) ــ Duplicate داخل همین دسته (name + size)
    const dupErrors = [];
    const seen = new Set();
    for (const { file } of allFiles) {
      const key = dupKey(file);
      if (seen.has(key)) {
        dupErrors.push({ file, error: `فایل "${file.name}" تکراری است.` });
      } else {
        seen.add(key);
      }
    }

    // 8) اگر خطایی هست، نمایش و توقف
    const errors = [...unsupported, ...oversize, ...dupErrors];
    if (errors.length > 0) {
      displayUploadErrors(errors);          // حتماً نسخه‌ای که id صحیح دارد را استفاده کن
      $submitBtn.prop("disabled", false).text(originalText);
      return;
    }

    // 9) افزودن فایل‌های معتبر به FormData
    let imgIndex = 0, vidIndex = 0;
    for (const { file, type } of allFiles) {
      if (type === "image") formData.append(`images[${imgIndex++}]`, file);
      else if (type === "video") formData.append(`videos[${vidIndex++}]`, file);
    }
    formData.append("total_images", imgIndex);
    formData.append("total_videos", vidIndex);

    // 10) ارسال به API
    savePostChanges(currentPost.id, formData)
      .always(() => {
        $submitBtn.prop("disabled", false).text(originalText);
      });
  });
}


// 🔧 ابزار کمکی برای تبدیل src به File
async function urlToFile(url, filename, mimeType) {
    const res = await fetch(url)
    const blob = await res.blob()
    return new File([blob], filename, { type: mimeType })
}

function initializeDeleteFunctionality() {
    const $deleteBtn = $("#gallery-delete-post-btn")
    if ($deleteBtn.length === 0) return

    $deleteBtn.on("click", (e) => {
        e.preventDefault()

        if (confirm("آیا از حذف این پست مطمئن هستید؟")) {
          // console.log("Deleting post:", currentPost.id)
            deletePost(currentPost.id)
        }
    })
}

function removeMediaFromPost(index) {
    const mediaPreview = document.querySelector(`.media-preview[data-index="${index}"]`)

    if (confirm("آیا از حذف این رسانه مطمئن هستید؟")) {
        if (mediaPreview) {
            mediaPreview.classList.add("deleting")

            setTimeout(() => {
                currentPost.slides.splice(index, 1)
                displayCurrentMedia()

                if (currentPost.slides.length === 0) {
                    alert("هشدار: پست باید حداقل یک رسانه داشته باشد!")
                }
            }, 500)
        } else {
            currentPost.slides.splice(index, 1)
            displayCurrentMedia()

            if (currentPost.slides.length === 0) {
                alert("هشدار: پست باید حداقل یک رسانه داشته باشد!")
            }
        }
    }
}

// FIXED: Enhanced createSwiperSlides function with proper video rendering
function createSwiperSlides() {
    const $swiperWrapper = window.$(".gallery-modal-swiper .swiper-wrapper")
    if ($swiperWrapper.length === 0 || !currentPost.slides) return

    $swiperWrapper.empty()

    currentPost.slides.forEach((slide, index) => {
        const $slideDiv = window.$('<div class="swiper-slide"></div>')

        if (slide.type === "image") {
            const $img = window
                .$("<img>")
                .attr("src", slide.src)
                .attr("alt", `Slide ${index + 1}`)
                .attr("loading", "lazy")
                .on("error", function () {
                    window.$(this).attr("src", "/placeholder.svg?height=400&width=400")
                })
            $slideDiv.append($img)
        } else if (slide.type === "video") {
            const $video = window
                .$("<video>")
                .attr("src", slide.src)
                .attr("controls", true)
                .attr("muted", false)
                .attr("preload", "metadata")
                .on("error", function () {
                    window.$(this).replaceWith('<div class="video-error">خطا در بارگذاری ویدیو</div>')
                })
            $slideDiv.append($video)
        }

        $swiperWrapper.append($slideDiv)
    })
}

function updateSwiperVisibility() {
    const $navigation = window.$(
        ".gallery-modal-swiper .gallery-swiper-button-next, .gallery-modal-swiper .gallery-swiper-button-prev",
    )
    const $pagination = window.$(".gallery-modal-swiper .gallery-swiper-pagination")

    if (!currentPost || currentPost.slides.length <= 1) {
        $navigation.hide()
        $pagination.hide()
    } else {
        $navigation.show()
        $pagination.show()
    }
}

function initializeSwiper() {
    if (modalSwiper) {
        try {
            modalSwiper.destroy(true, true)
        } catch (error) {
            console.warn("Error destroying existing Swiper:", error)
        }
        modalSwiper = null
    }

    if (typeof window.Swiper === "undefined") {
        console.error("Swiper library not loaded")
        return
    }

    try {
        modalSwiper = new window.Swiper(".gallery-modal-swiper", {
            direction: "horizontal",
            loop: false,
            centeredSlides: true,
            slidesPerView: 1,
            spaceBetween: 0,
            navigation: {
                nextEl: ".gallery-modal-swiper .gallery-swiper-button-next",
                prevEl: ".gallery-modal-swiper .gallery-swiper-button-prev",
            },
            pagination: {
                el: ".gallery-modal-swiper .gallery-swiper-pagination",
                clickable: true,
                type: "fraction",
            },
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },
            mousewheel: {
                invert: false,
                forceToAxis: true,
            },
            speed: 300,
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 1,
            },
            on: {
                init: () => {
                    updateSwiperVisibility()
                    handleSlideChange()
                },
                slideChange: handleSlideChange,
                touchStart: pauseAllVideos,
            },
        })
    } catch (error) {
        console.error("Error initializing Swiper:", error)
        window
            .$(".gallery-modal-swiper .gallery-swiper-button-next, .gallery-modal-swiper .gallery-swiper-button-prev")
            .hide()
        window.$(".gallery-modal-swiper .gallery-swiper-pagination").hide()
    }
}

function handleSlideChange() {
    pauseAllVideos()

    if (modalSwiper && modalSwiper.slides) {
        try {
            const activeSlide = modalSwiper.slides[modalSwiper.activeIndex]
            if (activeSlide) {
                const $video = window.$(activeSlide).find("video")
                if ($video.length > 0) {
                    const video = $video[0]
                    video.muted = false // Ensure muted for autoplay
                    video.currentTime = 0

                    // Only play if modal is open
                    if ($("#gallery-post-modal").hasClass("active")) {
                        video.play().catch((e) => console.log("Video play failed:", e))
                    }
                }
            }
        } catch (error) {
            console.warn("Error handling slide change:", error)
        }
    }
}

function handleFileSelection(e) {
    const files = Array.from(e.target.files)
    addNewMediaFiles(files)
    e.target.value = ""
}

async function addNewMediaFiles(files) {
  const errors = [];
  const validFiles = [];
  const successes = [];

  // 1) فیلتر نوع فایل
  const supported = files.filter((file) => {
    const ok = isSupportedMedia(file);
    if (!ok) errors.push({ file, error: `نوع فایل پشتیبانی نمی‌شود: ${file?.name || "فایل ناشناس"}` });
    return ok;
  });

  // 2) ولیدیشن حجم
  for (const file of supported) {
    const v = validateFileSizeExact(file);
    if (!v.valid) errors.push({ file, error: v.error });
    else validFiles.push(file);
  }

  // 3) Duplicate با اسلایدهای فعلی + در همین batch
  const existingKeys = [];
  for (const media of currentPost.slides) {
    if (media.file instanceof File) existingKeys.push(dupKey(media.file));
  }
  const seenNew = new Set();
  const finalFiles = [];
  for (const file of validFiles) {
    const key = dupKey(file);
    if (existingKeys.includes(key)) {
      errors.push({ file, error: `فایل "${file.name}" قبلاً اضافه شده است.` });
      continue;
    }
    if (seenNew.has(key)) {
      errors.push({ file, error: `فایل "${file.name}" تکراری است.` });
      continue;
    }
    seenNew.add(key);
    finalFiles.push(file);
  }

  if (finalFiles.length === 0) {
    // هیچ فایل معتبری برای اضافه‌کردن نمانده → فقط خطا را نشان بده
    displayUploadReport({ successes: [], errors });
    if (errors.length === 0) alert("هیچ فایل معتبری برای آپلود انتخاب نشد.");
    return;
  }

  // 4) خواندن و افزودن نهایی
  let processed = 0;
  finalFiles.forEach((file) => {
    const reader = new FileReader();
    reader.onload = (e) => {
      currentPost.slides.push({
        type: file.type.startsWith("image/") ? "image" : "video",
        src: e.target.result,
        file,
        isNew: true,
        name: file.name,
        size: file.size,
      });
      successes.push(file);
      processed++;
      if (processed === finalFiles.length) {
        displayCurrentMedia();

        // گزارش ترکیبی: هم موفق‌ها و هم خطاها
        displayUploadReport({ successes, errors });
      }
    };
    reader.onerror = () => {
      errors.push({ file, error: "خطا در خواندن فایل" });
      processed++;
      if (processed === finalFiles.length) {
        displayCurrentMedia();
        displayUploadReport({ successes, errors });
      }
    };
    reader.readAsDataURL(file);
  });
}



function validateFileSize(file) {
    const isImage = file.type.startsWith("image/")
    const isVideo = file.type.startsWith("video/")

    if (isImage && file.size > FILE_SIZE_LIMITS.image) {
        return {
            valid: false,
            error: `تصویر "${file.name}" بزرگتر از 2MB است (${formatFileSize(file.size)})`,
        }
    }

    if (isVideo && file.size > FILE_SIZE_LIMITS.video) {
        return {
            valid: false,
            error: ` ویدیو "${file.name}" بزرگتر از 10MB است (${formatFileSize(file.size)})`,
        }
    }

    return { valid: true }
}

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes"

    const k = 1024
    const sizes = ["Bytes", "KB", "MB", "GB"]
    const i = Math.floor(Math.log(bytes) / Math.log(k))

    return Number.parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
}

async function checkForDuplicates(newFiles) {
    const duplicates = []
    const existingFiles = []

    for (const media of currentPost.slides) {
        if (media.file) {
            existingFiles.push({
                name: media.file.name,
                size: media.file.size,
            })
        }
    }

    for (const file of newFiles) {
        const existingDuplicate = existingFiles.some(
            (existing) => existing.name === file.name && existing.size === file.size,
        )

        if (existingDuplicate) {
            duplicates.push({
                file,
                error: `فایل "${file.name}" قبلاً اضافه شده است`,
            })
            continue
        }

        const duplicateInNewFiles =
            newFiles.filter((f) => f !== file && f.name === file.name && f.size === file.size).length > 0

        if (duplicateInNewFiles) {
            duplicates.push({
                file,
                error: `فایل "${file.name}" تکراری است`,
            })
        }
    }

    return duplicates
}

function displayUploadReport({ successes = [], errors = [] }) {
  const box = document.getElementById("gallery-upload-errors-id");
  if (!box) return;

  const hasErrors = errors.length > 0;
  const hasSuccess = successes.length > 0;

  if (!hasErrors && !hasSuccess) {
    box.style.display = "none";
    return;
  }

  const errHtml = hasErrors ? `
    <div class="upload-errors-section" style="margin-bottom:12px;">
      <div style="font-weight:600; margin-bottom:6px;">خطا در آپلود (${errors.length} مورد):</div>
      ${errors.map(({ file, error }) => `
        <div class="upload-error-item" style="display:flex; gap:8px; align-items:flex-start; margin-bottom:6px;">
          <svg class="upload-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;margin-top:2px;">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
          </svg>
          <div class="upload-error-text">
            <div class="upload-error-filename" style="font-weight:600;">${file ? file.name : "فایل نامشخص"}</div>
            <div>${error}</div>
          </div>
        </div>
      `).join("")}
    </div>` : "";

  const okHtml = hasSuccess ? `
    <div class="upload-success-section">
      <div class="upload-success" style="display:flex; align-items:center;">
        <svg style="width:16px;height:16px;margin-left:8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22,4 12,14.01 9,11.01"></polyline>
        </svg>
        ${successes.length} فایل با موفقیت اضافه شد.
      </div>
      <ul style="margin:6px 18px 0 0; font-size:12px; line-height:1.6;">
        ${successes.map(f => `<li>${f.name} (${(f.size/1024/1024).toFixed(2)} MB)</li>`).join("")}
      </ul>
    </div>` : "";

  box.innerHTML = errHtml + okHtml;
  box.style.display = "block";

  // اگر خطا داریم، مدت نمایش را طولانی‌تر کن
  const timeout = hasErrors ? 12000 : 4000;
  window.clearTimeout(box.__hideTimer);
  box.__hideTimer = window.setTimeout(() => {
    box.style.display = "none";
  }, timeout);
}



// Helper function to show success messages
function showSuccessMessage(message) {
    const successHtml = `
    <div id="gallery-success-message" class="gallery-success-message">
      <div class="gallery-success-content">
        <div class="gallery-success-icon">✅</div>
        <p>${message}</p>
        <button onclick="hideSuccessMessage()" class="gallery-success-close-btn">بستن</button>
      </div>
    </div>
  `

    $("#gallery-success-message").remove()
    $body.append(successHtml)
    $("#gallery-success-message").show()

    setTimeout(() => {
        hideSuccessMessage()
    }, 5000)
}

function hideSuccessMessage() {
    $("#gallery-success-message").remove()
}

function debugPostData() {
  /*  console.log("=== CURRENT POST DEBUG ===")
    console.log("Current Post Data:", currentPost)
*/
  /*  if (currentPost && currentPost.slides) {
        console.log("Post Slides Analysis:")
        currentPost.slides.forEach((slide, index) => {
            console.log(`Slide ${index}:`, {
                type: slide.type,
                hasFile: !!(slide.file instanceof File),
                hasId: !!slide.id,
                isNew: slide.isNew,
                fileName: slide.file ? slide.file.name : "N/A",
                fileSize: slide.file ? slide.file.size : "N/A",
                fileType: slide.file ? slide.file.type : "N/A",
                src: slide.src ? slide.src.substring(0, 50) + "..." : "N/A",
            })
        })
    } */

  //  console.log("Form Description:", $("#gallery-post-description-id").html())

    if (currentPost) {
        // Simulate FormData creation
        const newFiles = []
        const existingMedia = []

        currentPost.slides.forEach((slide, index) => {
            if (slide.file && slide.file instanceof File) {
                newFiles.push({
                    file: slide.file,
                    ordering: index + 1,
                    name: slide.file.name,
                    size: slide.file.size,
                    type: slide.file.type,
                })
            } else if (slide.id) {
                existingMedia.push({
                    id: slide.id,
                    ordering: index + 1,
                    src: slide.src,
                })
            }
        })

     /*   console.log("New files that would be sent:", newFiles)
        console.log("Existing media that would be sent:", existingMedia)
        console.log("Total slides:", currentPost.slides.length)
        console.log("New files count:", newFiles.length)
        console.log("Existing media count:", existingMedia.length) */
    }
 //   console.log("=== END DEBUG ===")
}

// Make debug function available globally
window.debugPostData = debugPostData

// Export functions for global access
window.galleryApp = {
    loadPosts,
    loadCurrentUserPosts,
    loadUserPosts,
    refreshPosts,
    openModal,
    closeModal,
    showComments,
    showedit,
    hideOverlay,
    hideErrorMessage,
    retryLastAction,
    refreshCSRFToken,
    getCSRFToken,
    getCurrentUserId,
    buildApiUrl,
    debugPostData, // Add debug function
}

window.$(() => {
    window.$("#gallery-comment-input-id").on("input", debounce(toggleSubmitButton, 300))
    window.$("#gallery-comment-form-id").on("submit", (e) => {
        e.preventDefault()
        addComment()
    })
    window.$("#gallery-posts-grid").on("click", ".comment-btn", function () {
        const postId = window.$(this).closest(".gallery-item").data("post-id")
        loadSinglePostAndOpenModal(postId)
        showComments()
    })
})
