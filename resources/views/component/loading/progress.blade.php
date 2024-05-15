<style>
div.emojionearea-editor img{
    display: none !important;
  }

  div.emojionearea.emojionearea-standalone .emojionearea-editor::before {
      content: unset !important;
  }

  img.img-plhd{
      height: 90px;
      width: 90px;
      border-radius: 100%;
      object-fit: contain;
      border: 1px solid #ffff;
  }

    #agentRegister select{
      height: 25px;
    }

  div#progess div.modal-content{
      border: 2px solid #4197E5;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      top: 0;
      margin: auto;
      /* left: 40%;
      top: 24%; */
      display: flex;
      background: var(--bs-white);
      overflow: hidden;
      width: 350px !important;
      height: 350px;
      padding: 0px;
      border-radius: 100%;
      padding: 0 !important;
      min-width: initial !important;
  }

  div#progess div.modal-content div.modal-body{
    width: 100%;
    text-align: center;
  }

  div#progess div.modal-content div.modal-body div.block-content div.btn-wrapper{
    font-weight: bold;
  }

  div#progess div.modal-content div.modal-body div.content{
        margin-top: 30%;
        margin-bottom: 25%;
    }


  #progess {
      /* display: none; */
      position: fixed;
      z-index: 99999;
      /* padding-top: 100px; */
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgb(0 0 0 / 68%);
  }


  .rotate {
    animation: rotation 3s infinite linear;
  }

  @keyframes rotation {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(359deg);
    }
  }

  #progess .btn-wrapper #progess_pre, #progess .btn-wrapper span{
    font-weight: bold;
    font-size: 29px;
  }


  @media all and (max-width: 1440px) {
  }


</style>

<div id="progess" class="modal" >
                  <!--  Modal content class="rotate" id="progess_img" -->
            <div class="modal-content" >

                    <div class="modal-body">

                        <div class="block-content">

                            <div class="content">
                                <img class="rotate" style="width: 87px" src="{{ asset("common_img/icon.svg") }}">
                            </div>

                            <div class="btn-wrapper">
                                <span id="progess_pre" > 0</span> <span>%</span>
                            </div>

                        </div>

                    </div>
            </div>
</div>