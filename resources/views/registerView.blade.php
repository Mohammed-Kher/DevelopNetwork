<!DOCTYPE html>
<html>

  <head>
    <title>Register</title>
  </head>

  <body>
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <h1>Register</h1>
    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
      </div>
      <div>
        <label for="phone_number">phone number:</label>
        <input type="phone_number" id="phone_number" name="phone_number" required>
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit">Register</button>
    </form>
  </body>

</html>