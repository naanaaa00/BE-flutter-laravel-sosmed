<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
@foreach ($posts as $post)
    <div>
        <p>Posted by: {{ $post->user->username }}</p>
        <p>{{ $post->body }}</p>
        @if ($post->image)
            <img src="{{ $post->image }}" alt="Post Image">
        @endif
        <p>Comments: {{ $post->comments_count }}</p>
        <p>Likes: {{ $post->likes_count }}</p>
        @if ($post->likes->contains('user_id', auth()->id()))
            <p>You liked this post!</p>
        @endif
    </div>
@endforeach

</body>
</html>