<form action="{{ $paymentGatewayUrl }}" method="post">
    <input type="hidden" name="signature" value="{{ $signature }}"/>
    <input type="hidden" name="orderNote" value="{{ $paymentData['orderNote'] }}"/>
    <input type="hidden" name="orderCurrency" value="{{ $paymentData['orderCurrency'] }}"/>
    <input type="hidden" name="customerName" value="{{ $paymentData['customerName'] }}"/>
    <input type="hidden" name="customerEmail" value="{{ $paymentData['customerEmail'] }}"/>
    <input type="hidden" name="customerPhone" value="{{ $paymentData['customerPhone'] }}"/>
    <input type="hidden" name="orderAmount" value="{{ $paymentData['orderAmount'] }}"/>
    <input type ="hidden" name="notifyUrl" value="{{ $paymentData['notifyUrl'] }}"/>
    <input type ="hidden" name="returnUrl" value="{{ $paymentData['returnUrl'] }}"/>
    <input type="hidden" name="appId" value="{{ $paymentData['appId'] }}"/>
    <input type="hidden" name="orderId" value="{{ $paymentData['orderId'] }}"/>
</form>