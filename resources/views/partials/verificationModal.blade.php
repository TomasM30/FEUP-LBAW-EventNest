<div class="modal" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">User verification benefits and conditions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-justify">
                    <h6>Exclusive Benefits:</h6>
                    <ul>
                        <li>Stand out from the crowd with a verified symbol next to your username.</li>
                        <li>Unlock the ability to sell tickets and monetize your events.</li>
                        <li>Enjoy all the standard features available to our users.</li>
                    </ul>

                    <h6>Terms and Conditions:</h6>
                    <ul>
                        <li>A nominal commission of 15% is applicable on every ticket sale.</li>
                        <li>Event cancellation is subject to reporting to the administration. Approval is granted only under severe circumstances.</li>
                        <li>Account deletion is permissible only when there are no ongoing ticket sales for any event.</li>
                        <li>This is a one-time process and cannot be reversed.</li>
                    </ul>

                    <form id="verificationForm" action="{{ route('user.verify', ['id' => $user->id]) }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="acceptTerms" style="border: 1px solid #ced4da;">
                            <label class="form-check-label" for="acceptTerms">I accept the terms and conditions</label>
                        </div>

                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="confirmCertainty" style="border: 1px solid #ced4da;">
                            <label class="form-check-label" for="confirmCertainty">I am certain about my decision</label>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitButton" disabled>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>