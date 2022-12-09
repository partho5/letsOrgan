<div class="singleUserPost" id="editable_poll_container{{ $post->id }}" style="display: none">
    <div class="col-md-12"> <!-- start poll heading portion -->
        <div class="postHeading col-md-12" >
            <input type="text" value="{{$post->question}}" id="editable_q{{ $post->id }}" class="form-control col-md-12">
        </div>
    </div> <!-- end poll heading portion -->
    <div class="col-md-12">
        <div id="editPollInputWrapper{{ $post->id }}" class="editPollInputWrapper">
            <?php $options = json_decode($post->options); ?>
            @if(isset($options))
                @foreach($options as $key => $option)
                    <?php
                    $pollId = $post->id;
                    $optionId = $key; //$key is the option_id
                    $myLibrary = new \App\Library\Library();
                    $checkedState = $myLibrary->pollCheckedStatus($pollId, $optionId);
                    ?>
                    <div class="form-group" id="edit_poll_option_cont{{ $post->id }}">
                        <div class="input-group col-md-6">
                            <input type="text" value="{{$option}}" poll_id="{{$pollId}}" option_id="{{$optionId}}" class="editable_poll_option form-control">
                            <span class="input-group-addon removePollOption">X</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="form-group">
            <div class="input-group">
                <button id="editPollOption" post-id="{{ $post->id }}" class="editPollOption btn btn-success">Add Option</button>
            </div>
        </div>
        <div class="col-md-12">
            <button class="spanBtn cancelUpdatePostBtn" post-type="poll" post-id="{{ $post->id }}" style="color: #fff; background-color: #f00">Cancel</button>
            <button class="spanBtn updatePostBtn" post-type="poll" post-id="{{ $post->id }}">Update</button>
        </div>
    </div>
    <div class="col-md-12"> <!-- start posted by portion -->
        @if($post->is_anonymous)
            <div class="text-right"><a href="/users/profile/id/0">Anonymous</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
        @else
            <div class="text-right"><a href="/users/profile/id/{{$post->user_id or ""}}">{{$post->username or ""}}</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
        @endif
    </div> <!-- end posted by portion -->
    <br><hr>
</div> <!-- end polls -->