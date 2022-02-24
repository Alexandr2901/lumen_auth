<?php

namespace Http\Controllers;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use TestCase;


class NewsControllerTest extends TestCase
{
//    use DatabaseTransactions;

    public function testIndexSuccess()
    {
        $this->get('/api/news?page=3&count=30&users[0]=1&tags[0]=omnis');
//        $this->get('/api/news?count=30');

        $this->seeJsonStructure([
            'data' => ['*' => [
                'title',
                'text',
                'category',
                'id',
                'tags' => ['*' => ['id', 'name']]
            ]
            ]]);
    }

    public function testStoreSuccess()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/api/news/', [
            'title' => 'qwe',
            'text' => 'asd',
            'category_id' => 3,
            'tags' => ['sdf', 'ghj'],
            'users' => [1, 2, 3],
        ]);

        $this->seeJsonStructure([
            'data' => [
                'title',
                'text',
                'category',
                'id',
                'tags' => ['*' => ['id', 'name']],
                'users' => ['*' => ['id', 'name']],
            ]]);

        $this->seeJson([
            'title' => 'qwe',
            'text' => 'asd',
        ]);
    }

    public function testShowSuccess()
    {
        $news = News::factory()->create();
        $this->get('/api/news/' . $news->id);

        $this->seeJsonStructure([
            'data' => [
                'title',
                'text',
                'category',
                'id',
                'tags' => ['*' => ['id', 'name']],
                'users' => ['*' => ['id', 'name']],
            ]
        ]);

        $this->seeJson([
            'title' => $news->title,
            'text' => $news->text,
            'category' => $news->category->name,
            'id' => $news->id,
        ]);
    }

    public function testUpdateSuccess()
    {
        $user = User::factory()->create();
        $news = News::factory()->create();

        $user->news()->save($news);
//        var_dump($user->news()->first()->title);
//        $news->users()->syncWithoutDetaching($user->id);
        $this->actingAs($user)->put('/api/news/' . $news->id, [
            'title' => 'test',
            'text' => 'test',
        ]);

        $news = News::find($news->id);

        $this->assertEquals('test', $news->title);
        $this->assertEquals('test', $news->text);
    }

    public function testDestroySuccess()
    {
        $user = User::factory()->create();
        $news = News::factory()->create();
        $user->news()->save($news);

        $this->actingAs($user)->delete('/api/news/' . $news->id);
        try {
            News::findOrFail($news->id);
        } catch (ModelNotFoundException $ex) {
            $this->assertFalse(false);
        }
    }
}
