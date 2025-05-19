// Exibir notificações
function showNotifications() {
  document.getElementById('notifications-overlay').style.display = 'flex';
}

// Fechar notificações
function closeNotifications() {
  document.getElementById('notifications-overlay').style.display = 'none';
}

// Exibir formulário para criar serviço
function openCreateService() {
  document.getElementById('create-service-overlay').style.display = 'flex';
}

// Fechar o overlay de criar serviço
function closeCreateService() {
  document.getElementById('create-service-overlay').style.display = 'none';
}

// Função para recuperar e exibir os serviços
function fetchServices() {
  fetch('get_services.php')
    .then(response => response.json())
    .then(services => {
      const servicesList = document.getElementById('services-list');
      servicesList.innerHTML = ''; // Limpa a lista atual de serviços

      if (services.length === 0) {
        document.getElementById('no-services-message').style.display = 'block'; // Exibe a mensagem
      } else {
        document.getElementById('no-services-message').style.display = 'none'; // Esconde a mensagem
      }

      services.forEach(service => {
        const serviceCard = document.createElement('div');
        serviceCard.classList.add('service-item');
        serviceCard.innerHTML = `
          <h4>${service.nome_do_servico}</h4>
          <p><strong>Descrição:</strong> ${service.descricao}</p>
          <p><strong>Valor por Hora:</strong> R$ ${service.valorhora}</p>
          <p><strong>Telefone:</strong> ${service.telefone}</p>
          <button onclick="removeServiceCard(this, ${service.id})">Excluir</button>
        `;
        servicesList.appendChild(serviceCard);
      });
    });
}

// Enviar dados para criar um novo serviço
document.getElementById('create-service-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const providerName = document.getElementById('provider-name').value;
  const serviceName = document.getElementById('service-name').value;
  const servicePrice = document.getElementById('service-price').value;
  const servicePhone = document.getElementById('service-phone').value;
  const providerId = 1; // Exemplo: ID do prestador

  // Enviar os dados para o PHP
  const formData = new FormData();
  formData.append('nome_do_servico', serviceName);
  formData.append('descricao', 'Descrição do serviço');
  formData.append('valorhora', servicePrice);
  formData.append('telefone', servicePhone);
  formData.append('id_prestador', providerId);

  fetch('add_service.php', {
    method: 'POST',
    body: formData,
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        alert(data.message);
        fetchServices(); // Recarrega os serviços
      } else {
        alert('Erro ao criar o serviço');
      }
      closeCreateService();
    });
});

// Remover um serviço (exemplo de exclusão)
function removeServiceCard(button, serviceId) {
  const card = button.parentElement;

  // Enviar solicitação para excluir o serviço no servidor
  fetch('delete_service.php', {
    method: 'POST',
    body: JSON.stringify({ id: serviceId }),
    headers: {
      'Content-Type': 'application/json',
    },
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        // Se a exclusão for bem-sucedida, remove o card da página
        card.remove();

        // Se não houver mais serviços, exibe a mensagem
        const servicesList = document.getElementById('services-list');
        const noServicesMessage = document.getElementById('no-services-message');
        if (servicesList.children.length === 0) {
          noServicesMessage.style.display = 'block'; // Mostra a mensagem
        }
      } else {
        alert('Erro ao excluir o serviço');
      }
    })
    .catch(error => {
      console.error('Erro ao excluir o serviço:', error);
    });
}

// Carregar os serviços na página ao abrir
window.onload = function() {
  fetchServices();
};

// Função para exibir histórico de serviços (a ser implementada)
function viewHistory() {
  alert('Funcionalidade de histórico será implementada.');
  // Aqui você pode redirecionar ou mostrar o histórico de serviços.
}
