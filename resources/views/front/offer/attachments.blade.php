<div id="offer-attachments" style="margin: 40px 0;">
    <p>Załączniki:</p>
    @foreach ($attachments as $attachment)
        <a href="{{ asset('uploads/offer/' . $attachment->file) }}" target="_blank">{{ $attachment->name }}</a>
    @endforeach
</div>
