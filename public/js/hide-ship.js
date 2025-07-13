document.addEventListener('DOMContentLoaded', function () {
    const sameAsBilling = document.getElementById('sameAsBilling');
    const shippingFields = document.getElementById('shippingFields');
    const cardFields = document.getElementById('cardFields');
    const momoFields = document.getElementById('momoFields');
    const methodRadios = document.querySelectorAll('input[name="payment_method"]');
    
    
    methodRadios.forEach(radio => {
    radio.addEventListener('change', () => {
      if (radio.value === 'card') {
        cardFields.style.display = 'block';
        momoFields.style.display = 'none';
      } else {
        cardFields.style.display = 'none';
        momoFields.style.display = 'block';
      }
    });
    });
    function toggleShipping() {
        if (sameAsBilling.checked) {
            shippingFields.style.display = 'none';
        } else {
            shippingFields.style.display = 'block';
        }
    }

    sameAsBilling.addEventListener('change', toggleShipping);

    toggleShipping(); 
});    
