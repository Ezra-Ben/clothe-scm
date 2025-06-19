
function addField(wrapperId, fieldName, placeholderText) {
    const wrapper = document.getElementById(wrapperId);
    const index = wrapper.querySelectorAll('input').length + 1;


    const input = document.createElement('input');
    input.type = 'text';
    input.name = fieldName;

    input.placeholder = `${placeholderText} ${index}`;
    input.classList.add('form-control', 'mb-1');

    wrapper.appendChild(input);
}
