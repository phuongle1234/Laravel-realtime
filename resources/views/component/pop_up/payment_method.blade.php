
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
          <div class="modal-body">
            <h2 class="pp-tit">お支払方法選択</h2>
            <form class="setting-content setting-card-box">
              <ul>
              @foreach( $_card as $key => $_row )
                <li {{ $_row->id == $_default ? 'class=active' : null }} >
                  <svg class="icon-check" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none" onclick="updateDefaultCard('{{ $_row->id }}')">
                    <circle cx="20" cy="20" r="20" fill="#8FC31F"></circle>
                    <path d="M11 18.1379L18.5208 26L30 14" stroke="white" stroke-width="3" stroke-linecap="round"></path>
                  </svg><img src="./images/setting/debit_card.svg" alt="">
                  <span>**** **** **** {{ $_row->last4 }}</span>
                  <span onclick="updateEditCard('{{ $_row->id }}')" class="btn btn-outline-primary">編集する</span>
                </li>
              @endforeach
            </ul>
            <div class="text_center">
              <button class="btn btn-outline-primary btn-image btn-fullw" type="button"
              data-bs-dismiss="modal" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#card">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M13 0C11.8954 0 11 0.89543 11 2V11H2C0.895431 11 0 11.8954 0 13C0 14.1046 0.89543 15 2 15H11V24C11 25.1046 11.8954 26 13 26C14.1046 26 15 25.1046 15 24V15H24C25.1046 15 26 14.1046 26 13C26 11.8954 25.1046 11 24 11H15V2C15 0.895431 14.1046 0 13 0Z" fill="#00A29A"></path>
                </svg><span>お支払方法を追加する</span>
              </button>
              <span class="btn btn-primary btn-fullw" onclick="charges()">購入する</span>
            </div>
          </form>
        </div>
    </div>
