<h2>This is a 46elks Demo Module</h2>
 <p> First name: {$customer->firstname} </p>

 <p> Last name: {$customer->lastname} </p>

<p> Phone number: {$address->phone} </p>

 <p> Email: {$customer->email} </p>

 <p> Total paid: {$order->total_paid} </p>

<p> {$sms_error_code} </p>

<fieldset>
 <form method="post">  
    <p>
      <input type="hidden" id="phoneNumber" name="phoneNumber" value={$address->phone}>
      <label>Set your SMS content: </label>
      <textarea name="SMSContent" rows="4" cols="50"></textarea>
      <input id="submit" name="submit" type="submit" value="Send SMS" class="button" />
    </p>
  </form>
</fieldset>

