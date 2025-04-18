<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // id カラム (unsigned bigint, primary key)
            $table->foreignId('user_id')->constrained('users'); // usersテーブルのidを外部キーとして参照
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // itemsテーブルのidを外部キーとして参照
            $table->text('comment'); // コメントは必須にするため、nullable() を削除
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_comments');
    }
}
