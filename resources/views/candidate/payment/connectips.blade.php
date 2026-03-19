<form action="{{ env('CONNECTIPS_TXN_URL') }}" method="POST">
    <input type="hidden" name="MERCHANTID" value="{{ $merchantId }}">
    <input type="hidden" name="APPID" value="{{ $appId }}">
    <input type="hidden" name="APPNAME" value="{{ $appName }}">
    <input type="hidden" name="TXNID" value="{{ $txnId }}">
    <input type="hidden" name="TXNDATE" value="{{ $txnDate }}">
    <input type="hidden" name="TXNCRNCY" value="NPR">
    <input type="hidden" name="TXNAMT" value="{{ $amountInPaisa }}">
    <input type="hidden" name="REFERENCEID" value="{{ $referenceId }}">
    <input type="hidden" name="REMARKS" value="{{ $remarks }}">
    <input type="hidden" name="PARTICULARS" value="{{ $particulars }}">
    <input type="hidden" name="TOKEN" value="{{ $token }}">
    <input type="hidden" name="REDIRECTURL" value="{{ $successUrl }}">
    <input type="hidden" name="ERRORURL" value="{{ $failureUrl }}">

    <button type="submit">Redirecting to ConnectIPS...</button>
</form>

<script>
    document.forms[0].submit();
</script>
