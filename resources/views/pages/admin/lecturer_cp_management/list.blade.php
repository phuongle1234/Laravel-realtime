@extends('layouts.admin.index')
@section('body_class','p-lecturer_cp list')
@section('content')
    <h2 class="tit-page">講師報酬管理-一覧</h2>
    <div class="table-style mt00 tableheadgreen">
        <table>
            <thead>
            <form method="POST" id="form-submit" action="{{ URL::current() }}">
                @csrf
                <tr>
                    <th class="nowrap">
                        <div class="search-input">
                            <input class="form-control" type="text" name="code" value="{{ isset($code) ? $code : null }}" placeholder="講師ID" maxlength="10">
                            <button class="search-icon"><img src="{{ asset('../common_img/icon-search.svg') }}" alt="search"></button>
                        </div>
                    </th>
                    <th class="nowrap">
                        <div class="search-input">
                            <input class="form-control" type="text" name="name" value="{{ isset($name) ? $name : null }}" placeholder="講師ユーザー名" maxlength="20">
                            <button class="search-icon"><img src="{{ asset('../common_img/icon-search.svg') }}" alt="search"></button>
                        </div>
                    </th>
                    <th>現在の報酬</th>
                    <th>アクション</th>
                </tr>
            </form>
            </thead>
            <tbody>

            @if(!empty($lecturers))
                @foreach($lecturers as $lecturer)
                    <tr>
                        <td>{{ $lecturer->code ?? "" }}</td>
                        <td>{{ $lecturer->name ?? "" }}</td>
                        <td>{{ number_format($lecturer->reward) ?? 0 }}</td>
                        <td>
                            <div class="fcc"><a class="btn btn-primary btn-custom" href="{{ route('admin.lecturerCPManagement.edit',['id' => $lecturer->id]) }}">編集</a></div>
                        </td>
                    </tr>
                @endforeach
            @endif

            </tbody>
        </table>
    </div>
    {!! $lecturers->appends(request()->input())->links('component.admin.pager') !!}

@endsection

@section('custom_js')

<script>

</script>
<script src="{{ asset('js/form.js') }}"></script>
@endsection