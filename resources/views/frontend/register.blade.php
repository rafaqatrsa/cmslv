@extends('frontend.layouts.app')

@section('content')
    <section>
        <h1>Register</h1>

        <form method="POST" action="{{ route('frontend.register.store') }}">
            @csrf

            <div>
                <label for="register-branch">Branch</label>
                <select id="register-branch" name="brc_id">
                    <option value="">Select branch</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" @selected((string) old('brc_id') === (string) $branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('brc_id')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="register-name">Name</label>
                <input id="register-name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="register-contact">Contact</label>
                <input id="register-contact" name="contact" type="text" value="{{ old('contact') }}" required>
                @error('contact')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="register-email">Email</label>
                <input id="register-email" name="email" type="email" value="{{ old('email') }}">
                @error('email')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="register-father-name">Father Name</label>
                <input id="register-father-name" name="father_name" type="text" value="{{ old('father_name') }}">
                @error('father_name')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="register-address">Address</label>
                <textarea id="register-address" name="address" required>{{ old('address') }}</textarea>
                @error('address')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="register-description">Message</label>
                <textarea id="register-description" name="description">{{ old('description') }}</textarea>
                @error('description')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">Submit registration</button>
        </form>
    </section>
@endsection
