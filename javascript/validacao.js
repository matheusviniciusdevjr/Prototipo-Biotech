const form = document.querySelector('form'); 


const campos = [
    document.getElementById('endereco'),        
    document.getElementById('nome_completo'),  
    document.getElementById('cpf'),            
    document.getElementById('email'),         
    document.getElementById('telefone'),       
    document.getElementById('senha'),          
    document.getElementById('confirmar_senha'),
    document.getElementById('palavra_chave')   
];


const spans = document.querySelectorAll('.span-required');


const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
const telefoneApenasDigitosRegex = /^\d+$/;
const enderecoRegex = /^[a-zA-ZÀ-ÿ\s]+ - [a-zA-Z]{2}$/; 
const senhaRegex = /^(?=.*[A-Z])(?=.*[\W_]).{5,10}$/; 


$('#cpf').mask('000.000.000-00');
$('#telefone').mask('(00) 00000-0000');


form.addEventListener('submit', (event) => {
    event.preventDefault();
    let formIsValid = true;

    if (!validarEndereco(0)) formIsValid = false;       
    if (!validarNomeCompleto(1)) formIsValid = false;   
    if (!validarCPF(2)) formIsValid = false;            
    if (!validarEmail(3)) formIsValid = false;         
    if (!validarTelefone(4)) formIsValid = false;      

  
    const senhaValida = validarSenha(5);                  
    if (!senhaValida) formIsValid = false;

    if (!compararSenhas(6)) formIsValid = false;        
    
   

    if (formIsValid) {
        form.submit(); 
        console.log('Formulário pronto para envio!');
    } else {
        console.log('Por favor, corrija os erros do formulário.');
    }
});


function setError(index, mensagem) {
    campos[index].style.border = '2px solid red';
    spans[index].textContent = mensagem;
    spans[index].style.display = 'block';
    spans[index].style.color = '#ffc107'; 
    return false;
}

function removeError(index, dica = '') {
    campos[index].style.border = '';
    spans[index].textContent = dica;
    spans[index].style.display = dica ? 'block' : 'none';
    return true;
}


function validarEndereco(index) {
    const valor = campos[index].value.trim();
    if (valor.length === 0) {
        return setError(index, "O campo Endereço é obrigatório.");
    }
  
    if (!enderecoRegex.test(valor)) {
        return setError(index, "O Endereço deve estar no formato 'Cidade - UF' (Ex: São Paulo - SP).");
    }
    return removeError(index);
}


function validarNomeCompleto(index) {
    const valor = campos[index].value.trim();
    if (valor.length === 0) {
        return setError(index, "O Nome Completo é obrigatório.");
    }
    if (valor.length > 150) {
        return setError(index, "O Nome Completo deve ter no máximo 150 caracteres.");
    }
    return removeError(index);
}


function validarCPF(index) {
    const valor = campos[index].value;
    const apenasDigitos = valor.replace(/\D/g, ''); 

    if (valor.length === 0) {
        return setError(index, "O campo CPF é obrigatório.");
    }

  
    if (/[a-zA-Z]/.test(valor)) {
        return setError(index, "O campo CPF só aceita números.");
    }
    
  
    if (apenasDigitos.length !== 11) {
        return setError(index, "O CPF deve ter 11 dígitos (incluindo a formatação '000.000.000-00').");
    }

  
    if (/^(\d)\1{10}$/.test(apenasDigitos)) {
        return setError(index, "CPF inválido: não pode conter todos os dígitos iguais.");
    }
    
    return removeError(index);
}


function validarEmail(index) {
    const valor = campos[index].value.trim();
    if (valor.length === 0) {
        return setError(index, "O campo E-mail é obrigatório.");
    }
    if (!emailRegex.test(valor)) {
        return setError(index, "E-mail inválido. O formato correto é: exemplo@dominio.com.");
    }
    return removeError(index);
}


function validarTelefone(index) {
    const valor = campos[index].value;
    const apenasDigitos = valor.replace(/\D/g, ''); 

    if (valor.length === 0) {
        return setError(index, "O campo Telefone é obrigatório.");
    }
    
  
    if (/[a-zA-Z]/.test(valor)) {
        return setError(index, "O campo Telefone só pode aceitar números.");
    }

  
    if (apenasDigitos.length < 10 || apenasDigitos.length > 11) {
         return setError(index, "Telefone inválido. Deve ter 10 ou 11 dígitos (incluindo DDD).");
    }

    return removeError(index);
}


function validarSenha(index) {
    const senha = campos[index].value;
    const dica = "Mínimo 5 e máximo 10 caracteres, 1 letra maiúscula e 1 caractere especial (Ex: @, #, $).";

    if (senha.length === 0) {
        return setError(index, "O campo Senha é obrigatório.");
    }

 
    if (senha.length < 5) {
        return removeError(index, dica);
    }
    
    if (!senhaRegex.test(senha)) {
        return setError(index, "Senha fraca. " + dica);
    }


    return removeError(index);
}


function compararSenhas(index) {
   
    const senha = campos[index - 1].value; 
    const confirmar = campos[index].value; 

    if (confirmar.length === 0) {
        return setError(index, "A confirmação da senha é obrigatória.");
    }
    
   
    if (!validarSenha(index - 1)) {
        return setError(index, "As senhas não coincidem ou a Senha Principal está inválida.");
    }
    
    if (senha !== confirmar) {
        return setError(index, "As senhas não coincidem.");
    }

    return removeError(index);
}


campos[1].addEventListener('input', () => validarNomeCompleto(1)); 
campos[2].addEventListener('input', () => validarCPF(2)); 
campos[3].addEventListener('input', () => validarEmail(3)); 
campos[4].addEventListener('input', () => validarTelefone(4)); 
campos[0].addEventListener('input', () => validarEndereco(0)); 
// Event Listeners de Senha
campos[5].addEventListener('input', () => { 
    validarSenha(5); 
    compararSenhas(6); 
});

campos[6].addEventListener('input', () => compararSenhas(6)); 


campos[5].addEventListener('focus', () => { 
    if (campos[5].value.length < 5) {
         removeError(5, "Mínimo 5 e máximo 10 caracteres, 1 letra maiúscula e 1 caractere especial (Ex: @, #, $).");
    }
});

campos[5].addEventListener('blur', () => { 
    if (campos[5].value.length > 0 && !senhaRegex.test(campos[5].value)) {
        setError(5, "Senha fraca. Mínimo 5 e máximo 10 caracteres, 1 letra maiúscula e 1 caractere especial (Ex: @, #, $).");
    } else if(campos[5].value.length === 0) {
        removeError(5, "");
    }
});