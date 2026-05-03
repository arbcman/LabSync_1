<div>
    <!-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh -->
</div>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" style="color: red">
        Log Out !
    </button>
</form>
