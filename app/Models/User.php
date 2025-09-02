<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    //------------- start attributes

    public function birthCity()
    {
        return $this->belongsTo(City::class, 'birth_city_id');
    }

    public function deathCity()
    {
        return $this->belongsTo(City::class, 'death_city_id');
    }
    public function profileImage()
    {
        return $this->morphOne(\App\Models\Gallery::class, 'galleryable');
    }

    public function getImageUrlAttribute()
    {
        return $this->imageUrl();
    }

    public function imageUrl()
    {
        return $this->image ? asset($this->image) : asset('/back/app-assets/images/portrait/small/default.jpg');
    }

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->image ? asset($this->image) : asset('back/app-assets/images/portrait/small/default.jpg');
    }
    public function media()
    {
        return $this->morphMany(Gallery::class, 'galleryable'); // or 'imageable' if that's your naming
    }

    //------------- end attributes

    //------------- start relations

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Order::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function postcomment()
    {
        return $this->hasMany(PostComment::class);
    }

    public function views()
    {
        return $this->hasMany(Viewer::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites', 'user_id', 'product_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function referralCategories()
    {
        return $this->belongsToMany(Category::class, 'referral_categories_pivot')->withPivot(['percentage']);
    }

    public function referralProducts()
    {
        return $this->belongsToMany(Product::class, 'referral_products_pivot')->withPivot(['percentage']);
    }

    public function settelements()
    {
        return $this->hasMany(Settlement::class);
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id')->where('status', 'قبول شده');
    }

    public function followings()
    {
        return $this->hasMany(Follow::class, 'follower_id')->where('status', 'قبول شده');
    }


    public function closeUsers()
    {
        return $this->belongsToMany(User::class, 'close_users', 'owner_id', 'close_user_id')
            ->withTimestamps();
    }

    public function pendingFollowRequests()
    {
        return $this->hasMany(Follow::class, 'following_id')
            ->where('status', 'pending');
    }

    public function verification()
    {
        return $this->hasMany(UserVerfication::class);
    }

    public function messages()
    {
        return $this->hasMany(UserMessage::class);
    }

    public function familyTree(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(\App\Models\Gallery::class, 'galleryable')
            ->where('image', 'like', 'uploads/famillytree/%'); // ✅ همین کافیست
    }

    public function countries()
    {
        $this->hasMany(Countries::class);
    }
    //------------- end relations

    public function hasBought(Price $price)
    {
        $orders = $this->orders()->where('status', 'paid')->pluck('id');

        $bought = DB::table('order_items')->whereIn('order_id', $orders)->where('price_id', $price->id)->exists();

        return $bought;
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return $role->intersect($this->roles)->count();
    }

    public function isAdmin()
    {
        return $this->level == 'admin' || $this->level == 'creator';
    }

    public function isCreator()
    {
        return $this->level == 'creator';
    }

    public function getCart()
    {
        return $this->cart()->firstOrCreate();
    }

    public function getWallet()
    {
        return $this->wallet()->firstOrCreate(
            [],
            [
                'balance' => 0,
                'is_active' => true
            ]
        );
    }

    //------------- start scopes

    public function scopeFilter($query, $request)
    {
        if ($fullname = $request->input('query.fullname')) {
            $query->WhereRaw("concat(first_name, ' ', last_name) like '%{$fullname}%' ");
        }

        if ($email = $request->input('query.email')) {
            $query->where('email', 'like', '%' . $email . '%');
        }

        if ($username = $request->input('query.username')) {
            $query->where('username', 'like', '%' . $username . '%');
        }

        if ($level = $request->input('query.level')) {
            switch ($level) {
                case "admin": {
                    $query->where('level', 'admin');
                    break;
                }
                case "user": {
                    $query->where('level', 'user');
                    break;
                }
            }
        }

        if ($request->sort && $request->input('sort.field')) {
            switch ($request->sort['field']) {
                case 'fullname': {
                    $query->orderBy('first_name', $request->sort['sort'])->orderBy('last_name', $request->sort['sort']);
                    break;
                }
                default: {
                    if ($this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $request->sort['field'])) {
                        $query->orderBy($request->sort['field'], $request->sort['sort']);
                    }
                }
            }
        }

        return $query;
    }

    public function scopeCustomPaginate($query, $request)
    {
        $paginate = $request->paginate;
        $paginate = ($paginate && is_numeric($paginate)) ? $paginate : 10;

        if ($request->paginate == 'all') {
            $paginate = $query->count();
        }

        return $query->paginate($paginate);
    }

    public function scopeExcludeCreator($query)
    {
        return $query->where('level', '!=', 'creator');
    }

    public function posts()
    {
        return $this->hasMany(UserPost::class);
    }

    public function postlikes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function postcomments()
    {
        return $this->hasMany(PostComment::class);
    }


    public function galleries()
    {
        return $this->hasManyThrough(
            UserPost::class,         // Intermediate model
            'user_id',               // Foreign key on UserPost (user_id)
            'user_post_id',          // Foreign key on UserPostGallery (user_post_id)
            'id',                    // Local key on User model
            'id'                     // Local key on UserPost model
        );
    }




    //------------- end scopes
}
