<x-app-layout>
    <!-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div> -->

    <div class="container mt-4">

    <!--For  Practicing  -->

        <div class="row mt-5">
                @can('view user')
                    <a href="{{ route('dashboard') }}">Manage Users</a>
                @endcan

                @unless(auth()->user()->can('edit user') || auth()->user()->can('view user') || auth()->user()->can('delete user'))
                    <div>
                        <p>Sorry, you don't have permission to edit, view, or delete users.</p>
                    </div>
                @endunless


        </div>

    <!--For  Practicing  -->


        <div class="row mt-5">

            @if($count > 0 )

                <div class="col-md-3">

                    <ul class="list-group">

                        @foreach($users as $user)

                           @php
                               
                                $image = $user->getFirstMediaUrl();
                                
                                if(!$image){
                                    $image = 'images/default.png';
                                }
                            @endphp

                            <div class="list-group-item list-group-item-dark cursor-pointer user-list" data-id="{{ $user->id }}">
                                <img src="{{ $image }}" alt="no-image" class="user-image">
                                {{ @$user->name}}
                                <b><sup id="{{ $user->id }}-status" class="offline-status">Offline</sup></b>

                            </div>

                        @endforeach

                    </ul>

                </div>
                <div class="col-md-9">

                    <!-- <h1 class="start-head">Click to start Chat</h1> -->
                    <div class="chat-section">

                        <div id="chat-container">

                                

                               

                        </div>

                        <form action="" id="chat-form">

                        <input type="text" name="message" placeholder="Enter message here" id="message" class="border" required>
                        <input type="submit" value="Send Message" class="btn btn-primary btn-color">

                        </form>

                    </div>

                </div>

            @else

                <div class="col-md-12">
                    <h6>No Users found</h6>
                </div>

            @endif

        </div>

    </div>



    <!-- Modal for confirmation of deleting messages -->

    <div class="modal fade" id="deleteChatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" id="delete-chat-form">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="delete-chat-id">
                        <p>Are you sure you want to delete this message?</p>
                        <p><b id="delete-message"></b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <!-- Modal for confirmation of updating messages -->

    <div class="modal fade" id="updateChatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" id="update-chat-form">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="update-chat-id">
                        <input type="text" name="message" class="form-control" id="update-message"> 
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
