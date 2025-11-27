
document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('formPegada');
    if (form) {
       
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            calcularPegada();
        });
    }
});

function calcularPegada() {
   
    const transporte = Number(document.getElementById("transporte").value);
    const energia = Number(document.getElementById("energia").value);
    const carne = Number(document.getElementById("carne").value);
    const lixo = Number(document.getElementById("lixo").value);
    const roupas = Number(document.getElementById("roupas").value);
    const agua = Number(document.getElementById("agua").value);
    const eletronicos = Number(document.getElementById("eletronicos").value);
    const viagens = Number(document.getElementById("viagens").value);
    const localismo = Number(document.getElementById("localismo").value);
    const mobiliario = Number(document.getElementById("mobiliario").value);
   

    const total = transporte + energia + carne + lixo + roupas + agua + eletronicos + viagens + localismo + mobiliario;

   

    let nivel = "";
    let mensagem = "";
    let corResultado = "";

   
    if (total <= 18) {
       
        nivel = "Baixa Pegada 🌱";
        mensagem = `
            Sua pontuação é ${total}. Parabéns! Você é um exemplo de sustentabilidade.
            <br><br>
            Dicas para ir além:
            <ul>
                <li> Considere gerar sua própria energia renovável (solar, eólica).</li>
                <li> Apoie ativamente a legislação de proteção ambiental.</li>
                <li> Incentive amigos e familiares a adotarem práticas sustentáveis.</li>
            </ul>
        `;
        corResultado = "#4CAF50"; 
    } 
    else if (total <= 35) {
     
        nivel = "Pegada Moderada 🌿";
        mensagem = `
            Sua pontuação é ${total}. Você está no caminho certo, mas há espaço para melhorias!
            <br><br>
            **Principais Focos de Melhoria:
            <ul>
                <li> Transporte: Tente planejar rotas com transporte público ou carona solidária.</li>
                <li> Água e Energia: Instale aeradores nas torneiras e reduza o tempo de banho em 2 minutos.</li>
                <li> Dieta: Experimente ter 3 dias sem carne vermelha por semana (Troque por frango, peixe ou leguminosas).</li>
                <li> Lixo: Comprometa-se a reciclar 100% dos materiais recicláveis em casa.</li>
            </ul>
        `;
        corResultado = "#FFC107"; 
    } 
    else {
        
        nivel = "Pegada Alta 🌍";
        mensagem = `
            Sua pontuação é ${total}. Sua pegada está acima da média e requer atenção imediata para o bem do planeta.
            <br><br>
            Ações Urgentes Recomendadas:
            <ul>
                <li> Transporte: Faça a troca gradual do carro por bicicleta ou transporte público.</li>
                <li> Dieta: Adote uma segunda-feira sem carne para começar a reduzir o consumo.</li>
                <li> Consumo: Reduza drasticamente a compra de itens não essenciais (roupas, eletrônicos) e opte por usados.</li>
                <li> Energia: Troque todas as lâmpadas incandescentes por LED e desligue aparelhos da tomada quando não estiverem em uso.</li>
                <li> Reciclagem: Comece a separar o lixo *hoje* e procure o ponto de coleta mais próximo.</li>
            </ul>
        `;
        corResultado = "#F44336"; 
    }

    
    const resultadoDiv = document.getElementById("resultado");
    const tituloResultado = document.getElementById("textoResultado");

    tituloResultado.innerHTML = mensagem;
    resultadoDiv.style.display = "block";
    resultadoDiv.style.borderLeft = `5px solid ${corResultado}`;
    
   
    resultadoDiv.querySelector('.tituloResultado').innerHTML = `Seu resultado: <strong>${nivel}</strong>`;
}