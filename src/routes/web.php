<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;




use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\MailController;

Route::get('/send-test-email', [MailController::class, 'sendTestEmail']);


// メール認証の誘導ページ
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');

// メール認証の再送信
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// --- 商品関連 ---
Route::get('/', [ItemController::class, 'index'])->name('items.index'); // 商品一覧
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('show'); // 商品詳細

// 商品出品・編集・削除（認証ユーザー専用）
Route::middleware('auth')->group(function () {
    Route::get('/sell', [ItemController::class, 'create'])->name('sell'); // 出品ページ
    Route::post('/items', [ItemController::class, 'store'])->name('items.store'); // 商品登録
    Route::get('/items/{item_id}/edit', [ItemController::class, 'edit'])->name('items.edit'); // 商品編集ページ
    Route::post('/items/{item_id}/update', [ItemController::class, 'update'])->name('items.update'); // 商品更新
    Route::post('/items/{item_id}/delete', [ItemController::class, 'destroy'])->name('items.delete'); // 商品削除
    
    
    Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::get('/mylist', [ItemController::class, 'showMyList'])->name('mylist.index');
    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage.show');
    Route::get('/mypage/edit', [UserController::class, 'editProfile'])->name('mypage.edit');
    Route::post('/mypage/update', [UserController::class, 'updateProfile'])->name('mypage.update');
   
  
  

   


    Route::post('/address/store', [AddressController::class, 'store'])->name('address.store');
    Route::get('/address/edit/{id}', [AddressController::class, 'edit'])->name('address.edit');
    Route::get('/address/create', [AddressController::class, 'create'])->name('address.create');
});

// --- 購入関連 ---
Route::prefix('purchase')->name('purchase.')->group(function () {
      
        Route::post('/{item_id}/confirm', [PurchaseController::class, 'confirmPurchase'])->name('confirm'); // 購入確認
        Route::get('/complete', [PurchaseController::class, 'complete'])->name('complete'); // 購入完了ページ
        
        Route::get('/', [PurchaseController::class, 'index'])->name('index'); // 購入一覧ページ
        Route::get('{item_id}', [PurchaseController::class, 'purchase'])->name('show');

        // --- 住所変更関連（購入の一部として扱う） ---

    
        
    });

Route::middleware(['auth'])->group(function () {
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'changeAddress'])->name('address.change'); // 住所変更ページ
    Route::post('/address/{item_id}/update', [PurchaseController::class, 'updateAddress'])->name('Address.update'); // 住所更新処理
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchaseConfirm'])->name('purchase.confirm');
    Route::post('/purchase/{itemId}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/success', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/cancel', [PurchaseController::class, 'cancel'])->name('purchase.cancel');
    Route::get('/purchase/{id}', [PurchaseController::class, 'show'])->name('purchase.show');
   
});








// ログインページを指定
Fortify::loginView(fn() => view('auth.login'));

// 認証関連
Route::get('register', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // プロフィール編集ページ
    Route::get('/mypage/profile', [ProfileController::class, 'editProfile'])->name('profile.edit');

    // プロフィール更新処理
    Route::post('/mypage/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // プロフィール詳細（例：購入画面で使われるユーザープロフィール）
    Route::get('/mypage', [ProfileController::class, 'showMypage'])->name('mypage.show');

  
  
   
   
});


Route::middleware('auth')->get('/mylist', [ItemController::class, 'showMyList'])->name('mylist');

Route::post('/items/{id}/comment', [CommentController::class, 'store'])->name('items.comment');
Route::get('/items/{item}/comment-count', [CommentController::class, 'count'])->name('items.comment.count');







// アイテムに対する「いいね」を追加または削除するためのルート
Route::middleware(['auth'])->group(function () {
    // アイテムに「いいね」を追加する
    Route::post('/item/{item}/like', [LikeController::class, 'store'])->name('item.like');

    // アイテムから「いいね」を削除する
    Route::delete('/item/{item}/like', [LikeController::class, 'destroy'])->name('item.unlike');

    // ユーザーの「いいね」状態をトグルする
    Route::post('/toggle-like/{item}', [LikeController::class, 'toggleLike'])->name('items.toggleLike');
}); 



