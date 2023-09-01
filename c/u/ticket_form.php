<div class="card-header"><center><strong class="card-title" id="tftitle">Ticket Form</strong></center></div>

<div class="card-body">
<label>Ticket Number : </label>
<Input type="text" id="tickedid" name="ticketid" class="form-bline" maxlength="20" onkeyup="checkif1(this)">
<br><br>
<label>Channel : </label>

<input type="radio" class="btn-check" name="options-outlined" id="success-outlined" autocomplete="off" onchange="enablesubmit()" disabled>
<label class="btn btn-outline-success" for="success-outlined">Email</label>

<input type="radio" class="btn-check" name="options-outlined" id="danger-outlined" autocomplete="off" onchange="enablesubmit()" disabled>
<label class="btn btn-outline-danger" for="danger-outlined">Phone</label>

<input type="radio" class="btn-check" name="options-outlined" id="secondary-outlined" autocomplete="off" onchange="enablesubmit()" disabled>
<label class="btn btn-outline-secondary" for="secondary-outlined">Live Chat</label>
</div>

<div class="card-footer text-muted">
<button class="btn btn-outline-primary btn-block" id="submitticket" onclick="whensubmit()" disabled>Submit</button>
</div>

<div id="sqldiv"></div>