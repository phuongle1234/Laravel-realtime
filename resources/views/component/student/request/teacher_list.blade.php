<!-- old teacher suggestion  -->
<div id="app" style="margin-top: 20px;">

    <div class="list_old" v-show="list_old.length != 0">

        <div class="fsc request-dt-tit">
            <h2 class="teacher-title2">過去に高評価を付けた先生<span class="bgblue">@{{ showSubjectName  }}</span></h2>
        </div>
        <!-- <div class="note-item" data-bs-toggle="modal" data-bs-target="#cvcPopup"></div> -->

        <!-- <span>?</span> -->


        <div class="list-teacher">
            <div class="teacher-item" v-for="row in list_old.slice(0, 9)">
                <figure class="img-circle"><img :src="row.avatar_img" alt=""></figure>
                <div class="status-online" :class="{ active:row.online }"><span>@{{ row.name }}</span></div>
                <div class="rating"><img src="{{ asset('student/images/teacher_list/icon_star.svg') }}" alt="">
                    <span class="point">@{{ row.rating  }}</span><span class="number">@{{ row.people_rating  }}</span>
                </div>
                <p class="college">大学 : @{{ row.university_Code ? row.university.university_name : null }}</p>

                <ul class="list-courses list-courses-3item">
                    <li v-for="sub in row.subject">
                        <div>
                            <!-- <img :src="sub.icon" alt=""> -->
                            <span>@{{ sub.text_name }}</span>
                        </div>
                    </li>
                </ul>

                <a @click="chooseTeacher(row)" class="btn btn-primary" type="button">直接リクエストする</a>
            </div>
        </div>

    </div>

    <br><br>
    <!-- new teacher suggestion  -->
    <div class="list_new" v-show="list_new.length != 0">
        <div class="fsc request-dt-tit">
            <h2 class="teacher-title2">おすすめの先生<span class="bgblue">@{{ showSubjectName  }}</span></h2>
            <!-- <div class="note-item" data-bs-toggle="modal" data-bs-target="#cvcPopup"><span>?</span></div> -->
        </div>

        <div class="list-teacher">
            <div class="teacher-item" v-for="row in list_new">

                <figure class="img-circle"><img :src="row.avatar_img" alt=""></figure>

                <div class="status-online" :class="{ active:row.online }"><span>@{{ row.name }}</span></div>

                <div class="rating"><img src="{{ asset('student/images/teacher_list/icon_star.svg') }}" alt="">

                    <span class="point">@{{ row.rating  }}</span><span class="number">@{{ row.people_rating  }}</span>
                </div>

                <p class="college">大学 : @{{ row.university_Code ? row.university.university_name : null }}</p>

                <ul class="list-courses list-courses-3item">
                    <li v-for="sub in row.subject.slice(0, 9)">
                        <div>
                            <!-- <img :src="sub.icon" alt=""> -->
                            <span>@{{ sub.text_name }}</span>
                        </div>
                    </li>
                </ul>

                <button @click="chooseTeacher(row)" class="btn btn-primary" type="button">直接リクエストする</button>
            </div>
        </div>
    </div>
</div>