<!DOCTYPE html>
<html>
<head>
    <title>Share Archive</title>
</head>
<body>
<div>
    <h1>Share a archive with you from {{config("app.name")}}</h1>
    <p>{{$content}}</p>
    <p>You can access the archive using the following link:</p>
    <a href="{{ $link }}" target="_blank" style="width: 100%; position: relative">Click Here to view document</a>
    <div>OR</div>
    <a href="{{ $link }}">{{ $link }}</a>
</div>
</body>
</html>
