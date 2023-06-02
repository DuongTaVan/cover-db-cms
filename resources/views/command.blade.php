<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Document</title>
    <style>
        .list {
            padding-bottom: 10px;
        }

        .list a {
            margin-bottom: 5px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div style="justify-content: center; display: flex; margin-top: 10px;">
        <h1>Synchronized DB</h1>

    </div>
    <div style="justify-content: center; display: flex">
        <h3>{{ \Illuminate\Support\Facades\Session::get('msg')}}</h3>
    </div>

    <div class="content mt-3">

        <div class="list service">
            <div>Truncate all table in database</div>
            <a href="{{route('truncate_all')}}" class="btn btn-danger">Truncate all</a>
        </div>

        <div class="list service">
            <div>Service</div>
            <a href="{{route('banner')}}" class="btn btn-success">Banners</a>
            <a href="{{route('enterprise')}}" class="btn btn-success">Business partners</a>
            <a href="{{route('contact')}}" class="btn btn-success">Consultancies</a>
            <a href="{{route('popup')}}" class="btn btn-success">Popup</a>
            <a href="{{route('guide')}}" class="btn btn-success">Guide</a>
{{--            <a href="{{route('admin')}}" class="btn btn-success">Admin</a>--}}
            <a href="{{route('user')}}" class="btn btn-success">User</a>
            <a href="{{route('notifications_app')}}" class="btn btn-success">Notifications app</a>

            <a href="{{route('ebook')}}" class="btn btn-success">Ebook</a>
            <a href="{{route('feedback')}}" class="btn btn-success">Feedback</a>
        </div>

        <div class="list examination">
            <div>Flashcard</div>
            <a href="{{route('flashcard_topic')}}" class="btn btn-success">Flashcard topic</a>
            <a href="{{route('flashcard')}}" class="btn btn-success">Flashcard</a>
            <a href="{{route('flashcard_category')}}" class="btn btn-success">Flashcard category</a>
        </div>

        <div class="list examination">
            <div>Exam</div>
            <a href="{{route('remove_question_answer')}}" class="btn btn-success">Remove question answer, groupQS</a>
            <a href="{{route('examination')}}" class="btn btn-success">Examination & Topic</a>
            <a href="{{route('question_exam')}}" class="btn btn-success">Question exam</a>
            <a href="{{route('answer_exam')}}" class="btn btn-success">Answer exam</a>
            <a href="{{route('group_question_pivot')}}" class="btn btn-success">Group Question Pivot Exam</a>
            <a href="{{route('result_exam')}}" class="btn btn-success">Result exam</a>
        </div>

        <div class="list exercise-and-lesson">
            <div>Exercise & Lesson</div>
            <a href="{{route('lesson')}}" class="btn btn-success">Lesson</a>
            <a href="{{route('exercise')}}" class="btn btn-success">Exercise</a>
            <a href="{{route('answer')}}" class="btn btn-success">Answer</a>
            <a href="{{route('group_question_pivot_exercise')}}" class="btn btn-success">Group Question Pivot
                Exercise</a>
        </div>

        <div class="list activate">
            <div>Activate</div>
            <a href="{{route('activate')}}" class="btn btn-success">Activate</a>
        </div>

    </div>
</div>
</body>
</html>
