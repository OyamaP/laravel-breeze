<p>決済ページへリダイレクトします。</p>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ $publicKey }}");

    window.onload = function() {
        stripe.redirectToCheckout({
            sessionId: "{{ $session->id }}"
        }).then(function(result) {
            window.location.href = "{{ route('user.cart.cancel') }}";
        });
    };
</script>
{{--
    テストカード：4242 4242 4242 4242
    決済成功後確認：https://dashboard.stripe.com/test/payments
--}}
