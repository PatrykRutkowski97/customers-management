function jsUcfirst(string) 
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

const input = document.getElementById('search');

input.addEventListener('input', () => {
    const all = document.querySelectorAll('#surname');
    const all_name = document.querySelectorAll('#name');
    all.forEach(el => {
        if(el.innerHTML.indexOf(jsUcfirst(input.value))) {
            el.parentElement.style.setProperty('display','none');
            console.log('Nie pasuje = ' + el.innerHTML);
            
        }
        else {
            el.parentElement.style.setProperty('display','');
            console.log('Pasuje = ' + el.innerHTML);
        }
    })
    /*
    const all = document.querySelectorAll('#surname');
    all.forEach(el => {
        if(el.innerHTML.indexOf(jsUcfirst(input.value))) {
            el.parentElement.style.setProperty('display', 'none');
        }
        else {
            el.parentElement.style.setProperty('display', '');
        }
    })*/
});


