@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<h1>Thank you for your purchase, {{ $transaction->payer_names }}</h1>

<p>Total:${{ $transaction->payment_amount }}</p>

<p>Your tickets are confirmed. Here are your ticket numbers:</p>

<ul>
    @foreach ($tickets as $ticket)
        <li>{{ $ticket }}</li>
    @endforeach
</ul>
<img src="https://impeccablesolutionszw.com/wp-content/uploads/2025/08/cropped-IS-Logo_Logo_577px-300x80.png" alt="Event Logo" style="max-width: 150px; margin-top: 15px;">

<p>Event: {{ $event->name ?? 'N/A' }}</p>
<p>Date: {{ $event->date ?? 'N/A' }}</p>
<p>Venue: {{ $event->venue ?? 'N/A' }}</p>
<p>Time: {{ $event->time ?? 'N/A' }}</p>

<!-- Event Logo -->
<img src="{{ $event->event_image_url }}" alt="Event Logo" style="max-width: 150px; margin-top: 15px;">

<p>We look forward to seeing you!</p>

<div>
    {!! QrCode::size(150)->generate($transaction->merchant_reference) !!}
</div>
