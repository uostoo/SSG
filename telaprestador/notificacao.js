// Função para mostrar as notificações
function showNotifications() {
    document.getElementById('notifications-overlay').style.display = 'flex';
  }
  
  // Função para fechar o overlay de notificações
  function closeNotifications() {
    document.getElementById('notifications-overlay').style.display = 'none';
  }
  
  // Função para recuperar e exibir as notificações
  function fetchNotifications() {
    fetch('get_notifications.php')
      .then(response => response.text()) // Recebe a resposta como texto
      .then(data => {
        console.log('Dados recebidos:', data);
        
        // Verifica se a resposta é HTML (erro do servidor) antes de tentar analisar como JSON
        if (data.startsWith('<')) {
          console.error('Erro no servidor, resposta HTML:', data);
          return;
        }
  
        try {
          const notifications = JSON.parse(data); // Tenta fazer o parsing do JSON
          const notificationsContent = document.getElementById('notifications-content');
          notificationsContent.innerHTML = ''; // Limpa a lista de notificações
  
          if (notifications.error) {
            notificationsContent.innerHTML = `<p>${notifications.error}</p>`;
            return;
          }
  
          if (notifications.length === 0) {
            notificationsContent.innerHTML = '<p>Não há notificações.</p>';
          } else {
            notifications.forEach(notification => {
              // Converte o formato da data de 'YYYY-MM-DD HH:mm:ss' para 'YYYY-MM-DDTHH:mm:ss'
              const formattedDate = notification.tempo.replace(" ", "T");
              const date = new Date(formattedDate); // Cria o objeto Date
  
              const notificationCard = document.createElement('div');
              notificationCard.classList.add('notification-item');
              notificationCard.innerHTML = `
                <p><strong>Cliente:</strong> ${notification.cliente_nome}</p>
                <p><strong>Serviço:</strong> ${notification.nome_do_servico}</p>
                <p><strong>Data:</strong> ${date.toLocaleString()}</p>
                <button onclick="acceptNotification(${notification.id})">Aceitar</button>
                <button onclick="denyNotification(${notification.id})">Negar</button>
              `;
              notificationsContent.appendChild(notificationCard);
  
              // Log para verificar a data formatada
              console.log('Data formatada:', date.toLocaleString());
            });
          }
        } catch (error) {
          console.error('Erro ao processar a resposta:', error);
        }
      })
      .catch(error => {
        console.error('Erro ao buscar notificações:', error);
      });
  }
  
  

  
  // Função para aceitar uma notificação
  async function acceptNotification(notificationId) {
    console.log("ID do agendamento para aceitar:", notificationId); // Depuração

    try {
        const response = await fetch(`accept_notification.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: notificationId }), // Envia o ID no corpo da requisição
        });

        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();
        console.log("Agendamento aceito com sucesso:", data);
    } catch (error) {
        console.error("Erro ao aceitar o agendamento:", error);
    }
}

async function denyNotification(notificationId) {
    console.log("ID do agendamento para recusar:", notificationId); // Depuração

    try {
        const response = await fetch(`deny_notification.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: notificationId }), // Envia o ID no corpo da requisição
        });

        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();
        console.log("Agendamento recusado com sucesso:", data);
    } catch (error) {
        console.error("Erro ao recusar o agendamento:", error);
    }
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
            <button onclick="openServiceDetail(${service.id})">Ver Detalhes</button>
          `;
          servicesList.appendChild(serviceCard);
        });
      })
      .catch(error => {
        console.error('Erro ao buscar serviços:', error);
      });
  }
  
  // Função para abrir o overlay de detalhes do serviço
  function openServiceDetail(serviceId) {
    // Aqui você pode adicionar a lógica para buscar detalhes do serviço
    console.log('Exibindo detalhes do serviço com ID:', serviceId);
    // Exemplo de overlay
    alert(`Exibindo detalhes do serviço com ID: ${serviceId}`);
  }
  
  // Função para abrir o overlay de criação de serviço
  function openCreateService() {
    document.getElementById('create-service-overlay').style.display = 'flex';
  }
  
  // Função para fechar o overlay de criação de serviço
  function closeCreateService() {
    document.getElementById('create-service-overlay').style.display = 'none';
  }
  
  // Função para criar um novo serviço
  document.getElementById('create-service-form').addEventListener('submit', function (e) {
    e.preventDefault();
  
    const providerName = document.getElementById('provider-name').value;
    const serviceName = document.getElementById('service-name').value;
    const servicePrice = document.getElementById('service-price').value;
    const servicePhone = document.getElementById('service-phone').value;
  
    const serviceData = {
      provider_name: providerName,
      service_name: serviceName,
      service_price: servicePrice,
      service_phone: servicePhone
    };
  
    fetch('create_service.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(serviceData)
    })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          alert('Serviço criado com sucesso!');
          closeCreateService();
          fetchServices(); // Atualiza a lista de serviços
        } else {
          alert('Erro ao criar o serviço.');
        }
      })
      .catch(error => {
        console.error('Erro ao criar o serviço:', error);
      });
  });
  
  // Carrega os serviços e notificações quando a página carrega
  document.addEventListener('DOMContentLoaded', function () {
    fetchServices();
    fetchNotifications();
  });
  