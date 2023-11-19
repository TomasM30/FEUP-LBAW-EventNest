@extends('layouts.app')

@section('content')
    <!-- create_event.html -->
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Event</title>
    </head>
    <body>
    <h1>Create Event</h1>
    <form method="POST" action="{{ route('createEvent') }}">
        {{ csrf_field() }}
    
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        @error('title')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <br>
    
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        @error('description')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <br>
    
        <label for="type">Type:</label>
        <input type="text" id="type" name="type" required>
        @error('type')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <br>
    
        <label for="date">Date (d-m-y):</label>
        <input type="text" id="date" name="date" placeholder="dd-mm-yyyy" required>
        @error('date')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <br>
    
        <label for="capacity">Capacity:</label>
        <input type="number" id="capacity" name="capacity" required>
        @error('capacity')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <br>
    
        <label for="ticket_limit">Ticket Limit:</label>
        <input type="number" id="ticket_limit" name="ticket_limit" required>
        @error('ticket_limit')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <br>
    
        <label for="place">Place:</label>
        <input type="text" id="place" name="place" required>
        @error('place')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <br>
    
        <input type="submit" value="Create Event">
    </form>
    </body>
    </html>
