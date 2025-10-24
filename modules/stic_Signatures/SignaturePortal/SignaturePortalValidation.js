// **************************************************************************
// Include utilities
document.head.appendChild(Object.assign(document.createElement('script'), {
    src: 'modules/stic_Signatures/SignaturePortal/SignaturePortalUtils.js'
}));

// OTP form handling
const otpForm = document.getElementById('otpForm');
if (otpForm) {


    const inputs = document.querySelectorAll('.otp-input');
    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();

            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                inputs[index - 1].focus();

            }
        });
    });

    // Agrega la lógica para el botón de reenviar OTP por email
    const resendButtonOtpMail = document.getElementById('resend-otp-btn-email');
    resendButtonOtpMail.addEventListener('click', () => {
        resendOtp('email');
    });

    const resendButtonOtpPhone = document.getElementById('resend-otp-btn-phone-message');
    resendButtonOtpPhone.addEventListener('click', () => {
        resendOtp('phone');
    });

    /**
       *  Function to resend the OTP code to the user's email. 
       */
    function resendOtp(method) {

        const urlParams = new URLSearchParams(window.location.search);
        const url = 'index.php';
        const signerId = urlParams.get('signerId');

        const data = {
            entryPoint: "sticSign",
            signatureAction: "sendOtpCode",
            signerId: signerId,
            method: method,
        };

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data),
        }).then(response => {

            // Manage the response
            if (!response.ok) {
                throw new Error(MODS.LBL_PORTAL_NETWORK_ERROR);
            }
            return response.json();
            // Parsea la respuesta como JSON
        }
        ).then(data => {
            let messageKey = method === 'email' ? 'LBL_PORTAL_OTP_EMAIL_SENT' : 'LBL_PORTAL_OTP_PHONE_SENT';
            showAlert('success', MODS.LBL_PORTAL_ATTENTION, MODS[messageKey]);
        }
        ).catch(error => {
            console.error('Error:', error);
            showAlert('error', MODS.LBL_PORTAL_ERROR, MODS.LBL_PORTAL_ERROR_REQUEST_OTP_ALERT);
        }
        );

    }


    // send OTP form
    otpForm.addEventListener('submit', (e) => {
        const otpCode = document.getElementById('otp-code');
        otpCode.value = Array.from(inputs).map(input => input.value).join('');
    });
}
// End OTP form handling
// **************************************************************************


