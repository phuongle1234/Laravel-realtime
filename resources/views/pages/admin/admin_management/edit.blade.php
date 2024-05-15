@extends('layouts.admin.index')

@section('body_class','p-admin')
@section('class_main','p-content-admin')

@section('content')

<div class="table-style mt00 table-shawdow">
    <form method="post">
      @method('PUT')
      @csrf
                <table>
                  <tbody>
                    <tr>
                      <th>アドミン名</th>
                      <td>
                        <div class="fsc">
                          <input class="w300px form-control" name="name" type="text" value="{{ $item->name }}" maxlength="50">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th>ログインID</th>
                      <td>
                        <div class="fsc">
                          <input name="email" class="w300px form-control"  type="text" value="{{ $item->email }}"  maxlength="50">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th>パスワード</th>
                      <td>
                        <div class="fsc boxpassword">
                          <input class="form-control pass_log_id" name="password" type="password" value="{{ Crypt::decryptString($item->password_crypt) }}" maxlength="12"><i class="fas fa-eye toggle-password"></i>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
          <div class="fec mt20">
                <button type="submit" class="btn btn-primary btn-custom mr10" >登録</button>
                <input type="hidden" name="email_old" value="{{ $item->email }}">
                <a class="btn btn-danger btn-custom" href="{{ route('admin.adminManagement.list') }}">戻る</a>
          </div>
    </form>
</div>
@endsection

@section('custom_js')
<script>
      $(document).ready(function(){
        $("body").on('click', '.toggle-password', function() {
          $(this).toggleClass("fa-eye fa-eye-slash");
          var input = $(".pass_log_id");
          if (input.attr("type") === "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
        });
      });
    </script>
@endsection