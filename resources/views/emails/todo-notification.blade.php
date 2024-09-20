
<!DOCTYPE html>
<html>
<head>
    <title>Todo Notification</title>
</head>
<body>
<h1>Todo {{ $action }}</h1>
<p>A todo has been {{ $action }}.</p>
<p><strong>Title:</strong> {{ $todo->title }}</p>
<p><strong>Description:</strong> {{ $todo->description }}</p>
<p><strong>Status:</strong> {{ $todo->status }}</p>
</body>
</html>
