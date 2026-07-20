@extends('frontend.layouts.app')

@section('content')
    <section>
        <h1>Contact Us</h1>

        @if (! empty($settings?->address))
            <p>{{ $settings->address }}</p>
        @endif

        <form method="POST" action="{{ route('frontend.contact-us.store') }}">
            @csrf

            <div>
                <label for="contact-branch">Branch</label>
                <select id="contact-branch" name="brc_id">
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
                <label for="contact-name">Name</label>
                <input id="contact-name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-phone">Contact</label>
                <input id="contact-phone" name="contact" type="text" value="{{ old('contact') }}" required>
                @error('contact')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-email">Email</label>
                <input id="contact-email" name="email" type="email" value="{{ old('email') }}">
                @error('email')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-address">Address</label>
                <textarea id="contact-address" name="address">{{ old('address') }}</textarea>
                @error('address')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-description">Message</label>
                <textarea id="contact-description" name="description" required>{{ old('description') }}</textarea>
                @error('description')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">Send message</button>
        </form>
    </section>
@endsection
