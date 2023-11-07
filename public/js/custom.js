$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
    }
});


//document.ready starts here

$(document).ready(function(){

    $('.user-list').click(function(){

        $('#chat-container').html('');

        var getUserId = $(this).attr('data-id');
        receiver_id = getUserId;
    
        $('.start-head').hide();
        $('.chat-section').show();


        loadOldChats();

    });



    //saving chat
    $('#chat-form').submit(function(e){
        e.preventDefault();

        var message = $('#message').val();

        $.ajax({
            url: "/save-chat",
            method: "POST",
            data:{
                sender_id: sender_id,
                receiver_id: receiver_id,
                message: message 
            },
            success:function(response){
                if(response.success){
                    $('#message').val('');

                    let chat = response.data.message;

                    let html = `
                        <div class="current-user-chat" id='`+response.data.id+`-chat'>
                            <h5>
                                <span>`+chat+`</span>
                                <i class="bi-trash" aria-hidden="true" data-id='`+response.data.id+`' data-toggle="modal" data-target="#deleteChatModal"></i>
                                <i class="bi bi-pencil-square" aria-hidden="true" data-id='`+response.data.id+`' data-msg='`+response.data.message+`' data-toggle="modal" data-target="#updateChatModal"></i>
                            </h5>
                        </div>
                    `; 

                    $('#chat-container').append(html);
                    //scroll down
                    scrollChat();

                }
                else{
                    alert(response.message)
                }
            }
        });

    });



    //delete modal set
    $(document).on('click', '.bi-trash', function(){

        var id  = $(this).attr('data-id');

        $('#delete-chat-id').val(id);
        $('#delete-message').text($(this).parent().text());
    });


    //ajax for deleting msg
    $('#delete-chat-form').submit(function(e){
        e.preventDefault();

        var id = $('#delete-chat-id').val();


        $.ajax({
            'url': "/delete-chat",
            'type': "POST",
            'data': {id: id},
            success:function(response){
                // alert(response.message);
                if(response.success){
                    $('#'+id+'-chat').remove();
                    $(".modal-backdrop").remove();
                    $("#deleteChatModal").hide();

                }
            }

        });

    });



    //update modal set
    $(document).on('click', '.bi-pencil-square', function(){

        $('#update-chat-id').val($(this).attr('data-id'));

        $('#update-message').val($(this).attr('data-msg'));
    });


    //ajax for updating msg
    $('#update-chat-form').submit(function(e){
        e.preventDefault();

        var id = $('#update-chat-id').val();
        var msg = $('#update-message').val();



        $.ajax({
            'url': "/update-chat",
            'type': "POST",
            'data': {
                id: id, 
                message:msg
            },
            success:function(response){
                // alert(response.message);

                if(response.success){

                    $(".modal-backdrop").remove();
                    $("#updateChatModal").hide();

                    $('#'+id+'-chat').find('span').text(msg);

                    $('#'+id+'-chat').find('.bi-pencil-square').attr('data-msg', msg);

                }
                else{
                    alert(response.message);
                }
            }

        });

    });




});

//document.ready ends here



//load old chats

function loadOldChats(){

    $.ajax({
        url: "/load-chats",
        type: "POST",
        data: {
            sender_id: sender_id,
            receiver_id: receiver_id
        },
        success:function(response){
            if(response.success){

                let chats = response.data;
                let html = '';
                for(let i=0; i < chats.length; i++){
                    let addClass = '';
                    if(chats[i].sender_id == sender_id){
                        addClass = 'current-user-chat';

                    }
                    else{
                        addClass = 'distance-user-chat'
                    }

                    html+=`
                        <div class="`+addClass+`" id='`+chats[i].id+`-chat'>
                            <h5>
                                <span>`+chats[i].message+`</span>`;

                                if(chats[i].sender_id == sender_id){
                                    html+=`
                                        <i class="bi-trash" aria-hidden="true" data-id='`+chats[i].id+`' data-toggle="modal" data-target="#deleteChatModal"></i>
                                        <i class="bi bi-pencil-square" aria-hidden="true" data-id='`+chats[i].id+`' data-msg='`+chats[i].message+`' data-toggle="modal" data-target="#updateChatModal"></i>
                                    `;
                                }
                
                    html+=`
                            </h5>
                        </div>
                    `;
                }

                $('#chat-container').append(html);
                //scroll down
                scrollChat();

            }
            else{
                alert(response.message)

            }
        }

    });
}


//scroll down in chats

function scrollChat(){

    $('#chat-container').animate({
        scrollTop: $('#chat-container').offset().top + $('#chat-container')[0].scrollHeight
    },0);
}


window.onload=function(){

    //event listening for user stauts
    Echo.join('status-update')
    .here((users)=>{
        // console.log(users);

        for(let x=0; x < users.length; x++){

            if(sender_id != users[x]['id']){
            
                $('#'+ users[x]['id'] +'-status').removeClass('offline-status');
                $('#'+ users[x]['id'] +'-status').addClass('online-status');
                $('#'+ users[x]['id'] +'-status').text('Online');

            }
        }

    })

    .joining((user)=>{

        // console.log(user.name+" joined")
        $('#'+user.id+'-status').removeClass('offline-status');
        $('#'+user.id+'-status').addClass('online-status');
        $('#'+user.id+'-status').text('Online');
    })

    .leaving((user)=>{

        // console.log(user.name+" leaved")
        
        $('#'+user.id+'-status').addClass('offline-status');
        $('#'+user.id+'-status').removeClass('online-status');
        $('#'+user.id+'-status').text('Offline');

    })
    .listen('UserStatusEvent', (e)=>{
        console.log("yyy"+e);
    });



    //message event
    //for distant users
    Echo.private('broadcast-message')
    .listen('.getChatMessage', (data) => {
        // console.log(data);

        if(sender_id == data.chat.receiver_id && receiver_id == data.chat.sender_id){
            console.log(data);

            var html = `
                <div class="distance-user-chat" id='`+data.chat.id+`-chat'>
                    <h5>
                        <span>`+data.chat.message+`</span>
                    </h5>
                </div>    
            `;

            $('#chat-container').append(html);

            //scroll down
            scrollChat();
        }

    });



    //delete event listening for distant users

    Echo.private('message-deleted')
    .listen('MessageDeletedEvent', (data) => {
        // console.log("event id "+data.id)
        $('#'+data.id+'-chat').remove();

    });




    //update event listening for distant users
    
    Echo.private('message-updated')
    .listen('MessageUpdatedEvent', (data) => {

        $('#'+data.data.id+'-chat').find('span').text(data.data.message);

    });

}
