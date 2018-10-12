<div class="sticky-wrapper">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
        <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
        <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <div class="info-box" v-for="item in 10"><span class="info-box-icon bg-aqua"><span class="room_name">A-110</span></span><div class="info-box-content"><span class="info-box-number">Library</span><span class="info-box-number room_id hidden">117</span><span class="info-box-text text-muted">N/A Desk</span><span class="info-box-text text-muted">N/A Chair</span></div></div>
        </div>
        <div role="tabpanel" class="tab-pane" id="profile">
            <div class="info-box" v-for="item in 10"><span class="info-box-icon bg-aqua"><span class="room_name">A-110</span></span><div class="info-box-content"><span class="info-box-number">Library</span><span class="info-box-number room_id hidden">117</span><span class="info-box-text text-muted">N/A Desk</span><span class="info-box-text text-muted">N/A Chair</span></div></div>
        </div>
        <div role="tabpanel" class="tab-pane" id="messages">
            <div class="info-box" v-for="item in 10"><span class="info-box-icon bg-aqua"><span class="room_name">A-110</span></span><div class="info-box-content"><span class="info-box-number">Library</span><span class="info-box-number room_id hidden">117</span><span class="info-box-text text-muted">N/A Desk</span><span class="info-box-text text-muted">N/A Chair</span></div></div>
        </div>
        <div role="tabpanel" class="tab-pane" id="settings">
            <div class="info-box" v-for="item in 10"><span class="info-box-icon bg-aqua"><span class="room_name">A-110</span></span><div class="info-box-content"><span class="info-box-number">Library</span><span class="info-box-number room_id hidden">117</span><span class="info-box-text text-muted">N/A Desk</span><span class="info-box-text text-muted">N/A Chair</span></div></div>
        </div>
    </div>

    <div class="sticky-content">
        {{--<div class="info-box" v-for="item in 10"><span class="info-box-icon bg-aqua"><span class="room_name">A-110</span></span><div class="info-box-content"><span class="info-box-number">Library</span><span class="info-box-number room_id hidden">117</span><span class="info-box-text text-muted">N/A Desk</span><span class="info-box-text text-muted">N/A Chair</span></div></div>--}}
    </div>
</div>