@extends('manage-notify.index')
@section('title')
    {{'Chỉnh sửa tin tức'}}
@endsection
@section('content')
    <style>
        .note-editable {
            min-height: 200px;
        }
    </style>
    <div class="row well well-sm" style="margin-left: 0px;margin-right: 0px;margin-bottom: -1px">
        <a href="manage-notify" style="color: #333">
            <span class="company-register name-page-profile">Quản lý tin tức</span>
        </a>
        <span class="glyphicon glyphicon-menu-right small"></span>
        @foreach($notify as $n)
            <a href="" style="color: #333">
                <span class="company-register name-page-profile">Chỉnh sửa tin tức: {{$n->title}}</span>
            </a>
        @endforeach
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="edit-notify-form" method="POST" role="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @foreach($user as $u)
                    <input type="hidden" name="adminID" value="{{encrypt($u->id)}}">
                @endforeach
                @foreach($notify as $n)
                    <div class="form-group">
                        <input type="hidden" name="notifyID" value="{{encrypt($n->id)}}">
                        <label for="">Tiêu đề tin tức</label>
                        <textarea name="title" id="title" class="form-control" rows="3" required="required">
                            {{$n->title}}
                        </textarea>
                        <label for="" style="margin-top: 15px">Nội dung tin tức</label>
                        <textarea name="content" id="content" class="form-control" rows="3" required="required">
                            {{$n->content}}
                        </textarea>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Chỉnh sửa</button>
            </form>
        </div>
    </div>
    <script>
        $('#content').summernote();
    </script>
@endsection