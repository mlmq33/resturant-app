@extends('layout.app')

@php $pagename = "User" @endphp

@section('title')

    All Users

@endsection

@section('content')

    <div class="p-10">

        {{-- Page Name --}}
        <p class="text-3xl font-bold">All Users</p>

        {{-- Bread Crumb --}}
        <div class="flex flex-row items-center text-sm my-10">
            <a href="{{ route('home.' . auth()->user()->role->name ) }}" class="font-bold hover:text-blue-800">Home</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <p>All Users</p>
        </div>

        {{-- Add New Button --}}
        <a href="{{ route('user.create') }}">
            <div class="my-10 flex flex-row items-center justify-center space-x-4 border-2 border-green-800 hover:bg-green-800 text-green-800 hover:text-white font-semibold p-2 w-40 rounded-md cursor-pointer transform hover:scale-105 transition-all duration-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                </svg>
                <p>Add New</p>
            </div>
        </a>
        
        <div class="flex flex-row items-center lg:hidden animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            <p>Please scroll left to see the whole table</p>
        </div>


        <div class="w-full xl:w-2/3 mt-10 grid max-w-max">

            {{-- Users List --}}
            <div class="w-full grid grid-flow-row overflow-x-auto max-w-max">

                {{-- Table Titles --}}
                <div class="grid grid-cols-6 font-bold text-lg mb-1 px-4 py-4">
                    <p class="col-span-1">Full Name</p>
                    <p class="col-span-2">Email Address</p>
                    <p class="col-span-1">Role</p>
                    <p class="col-span-2">Action</p>
                </div>

                {{-- Table Rows --}}
                <div class="flex flex-col gap-y-2">

                    @foreach ($users as $user)

                        {{-- Single Table Row --}}
                        <div class="grid grid-cols-6 items-center min-w-max gap-x-1 border-3 border-green-800 rounded-md px-4 py-4 font-bold">
                            
                            <p class="col-span-1">{{ $user->first_name .' '. $user->last_name }}</p>
                            <p class="col-span-2">{{ $user->email }}</p>
                            <p class="col-span-1 capitalize">{{ $user->role->name }}</p>
                            <div class="col-span-2 flex flex-row gap-x-2">
                                <form action="{{ route('user.edit', $user->id) }}" method="GET">
                                    @csrf

                                    <button type="submit" class="border-3 border-green-800 hover:border-green-600 bg-green-800 hover:bg-green-600 text-white px-8 py-1 rounded-lg font-bold transform hover:scale-105 transition-all duration-500">Edit</button>
                                </form>

                                <button onclick="delete{{ $user->id }}()" class="border-3 border-black bg-black text-white px-8 py-1 rounded-lg font-bold transform hover:scale-105 transition-all duration-500" >Delete</button>

                            </div>

                        </div>


                        {{-- Modal --}}
                        <div id="modal{{ $user->id }}" class="hidden fixed z-10 inset-0 w-full h-full overflow-auto pt-20" style="background: rgba(0,0,0,0.5);">
                                            
                            <div id="modalBox{{ $user->id }}" class="bg-white w-3/4 lg:w-1/3 mx-auto p-10 rounded-lg text-center animate__animated animate__bounceInDown shadow-2xl">

                                {{-- Title --}}
                                <p class="text-3xl font-bold">Confirm Deletion</p>

                                {{-- Text --}}
                                <p class="my-10">Are you sure you want to delete this user account?</p>

                                {{-- Button --}}
                                <div class="flex flex-row items-center justify-center gap-5">

                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        {{-- Disable --}}
                                        <button type="submit" class="bg-green-800 text-white px-10 py-3 rounded-md">Yes</button>

                                    </form>

                                    <div onclick="cancel{{ $user->id }}()" class="bg-red-800 text-white px-10 py-3 rounded-md cursor-pointer">Cancel</div>
                                </div>
                            </div>  

                        </div>
                        
                        {{-- Modal Script (NOTE: Don't Move) --}}
                        <script>

                            // Delete Button
                            function delete{{ $user->id }}(){

                                const modal{{ $user->id }} =  document.querySelector('#modal{{ $user->id }}');
                                const modalBox{{ $user->id }} =  document.querySelector('#modalBox{{ $user->id }}');

                                if (modal{{ $user->id }}.classList.contains('hidden')){
                                    
                                    modal{{ $user->id }}.classList.remove('hidden');

                                }

                                if (modalBox{{ $user->id }}.classList.contains('animate__bounceOutUp')){
                                    
                                    modalBox{{ $user->id }}.classList.remove('animate__bounceOutUp');
                                    modalBox{{ $user->id }}.classList.add('animate__bounceInDown');

                                }
                            }

                            // Cancel Button
                            function cancel{{ $user->id }}(){

                                const modal{{ $user->id }} =  document.querySelector('#modal{{ $user->id }}');
                                const modalBox{{ $user->id }} =  document.querySelector('#modalBox{{ $user->id }}');

                                if (modalBox{{ $user->id }}.classList.contains('animate__bounceInDown')){
                                    
                                    modalBox{{ $user->id }}.classList.remove('animate__bounceInDown');
                                    modalBox{{ $user->id }}.classList.add('animate__bounceOutUp');

                                }
                                
                                setTimeout(function () {
                                    modal{{ $user->id }}.classList.add('hidden');
                                }, 800);

                            }

                            const modal{{ $user->id }} =  document.querySelector('#modal{{ $user->id }}');
                            const modalBox{{ $user->id }} =  document.querySelector('#modalBox{{ $user->id }}');
                            
                            window.addEventListener("click", function(event) {

                                if (event.target == modal{{ $user->id }}) {

                                    if (modalBox{{ $user->id }}.classList.contains('animate__bounceInDown')){
                                    
                                        modalBox{{ $user->id }}.classList.remove('animate__bounceInDown');
                                        modalBox{{ $user->id }}.classList.add('animate__bounceOutUp');

                                    }
                                    
                                    setTimeout(function () {
                                        modal{{ $user->id }}.classList.add('hidden');
                                    }, 800);

                                }
                            });

                        </script>

                    @endforeach

                </div>

            </div>

            <div class="w-full my-3 text-green-800">
                {{ $users->links() }}
            </div>

        </div>

    </div>

@endsection