<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
   public function testCreateComment()
   {
    $comment = new Comment();
    $comment->email = "ivriel@gmail.com";
    $comment->title = "Sample Title Update";
    $comment->comment = "Sample Comment";

    $comment->save();

    self::assertNotNull($comment->id);
   }

   public function testDefaultAttributeValues()
   {
    $comment = new Comment();
    $comment->email = "ivriel@gmail.com";

    $comment->save();

    self::assertNotNull($comment->id);
    self::assertNotNull($comment->title);
    self::assertNotNull($comment->comment);
   }
}
