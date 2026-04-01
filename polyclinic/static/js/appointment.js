// Загрузка специальностей при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    loadSpecialties();
    
    // Установка минимальной даты (сегодня)
    const dateInput = document.getElementById('date');
    dateInput.min = new Date().toISOString().split('T')[0];
    dateInput.value = new Date().toISOString().split('T')[0];
});

// Загрузка списка специальностей
async function loadSpecialties() {
    try {
        const response = await fetch('api/get_data.php?action=specialties');
        const result = await response.json();
        
        if (result.success) {
            const select = document.getElementById('specialty');
            result.data.forEach(specialty => {
                const option = document.createElement('option');
                option.value = specialty.id;
                option.textContent = specialty.name;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Ошибка загрузки специальностей:', error);
    }
}

// Загрузка врачей по специальности
document.getElementById('specialty').addEventListener('change', async function() {
    const specialtyId = this.value;
    const doctorSelect = document.getElementById('doctor');
    const serviceSelect = document.getElementById('service');
    
    doctorSelect.innerHTML = '<option value="">Загрузка...</option>';
    doctorSelect.disabled = true;
    
    if (!specialtyId) {
        doctorSelect.innerHTML = '<option value="">Сначала выберите специальность</option>';
        return;
    }
    
    try {
        const response = await fetch(`api/get_data.php?action=doctors&specialty_id=${specialtyId}`);
        const result = await response.json();
        
        if (result.success) {
            doctorSelect.innerHTML = '<option value="">Выберите врача</option>';
            result.data.forEach(doctor => {
                const option = document.createElement('option');
                option.value = doctor.id;
                option.textContent = `${doctor.last_name} ${doctor.first_name} ${doctor.middle_name}`;
                option.dataset.specialtyId = doctor.specialty_id;
                doctorSelect.appendChild(option);
            });
            doctorSelect.disabled = false;
            
            // Загрузка услуг для этой специальности
            loadServices(specialtyId);
        }
    } catch (error) {
        console.error('Ошибка загрузки врачей:', error);
        doctorSelect.innerHTML = '<option value="">Ошибка загрузки</option>';
    }
});

// Загрузка услуг
async function loadServices(specialtyId) {
    try {
        // В реальном проекте нужно сделать API для загрузки услуг по специальности
        // Пока загружаем все услуги
        const response = await fetch('api/get_data.php?action=services');
        // Для простоты пока не фильтруем по специальности
    } catch (error) {
        console.error('Ошибка загрузки услуг:', error);
    }
}

// Загрузка временных слотов
document.getElementById('doctor').addEventListener('change', loadTimeSlots);
document.getElementById('date').addEventListener('change', loadTimeSlots);

async function loadTimeSlots() {
    const doctorId = document.getElementById('doctor').value;
    const date = document.getElementById('date').value;
    const timeSlotsContainer = document.getElementById('time-slots');
    const timeInput = document.getElementById('time');
    
    timeSlotsContainer.innerHTML = '<p style="color: var(--text-light);">Загрузка...</p>';
    timeInput.value = '';
    
    if (!doctorId || !date) {
        timeSlotsContainer.innerHTML = '<p style="color: var(--text-light);">Выберите врача и дату</p>';
        return;
    }
    
    try {
        const response = await fetch(`api/get_data.php?action=time_slots&doctor_id=${doctorId}&date=${date}`);
        const result = await response.json();
        
        if (result.success) {
            timeSlotsContainer.innerHTML = '';
            
            if (result.data.length === 0) {
                timeSlotsContainer.innerHTML = '<p style="color: var(--text-light);">Нет доступных слотов</p>';
                return;
            }
            
            result.data.forEach(slot => {
                const slotDiv = document.createElement('div');
                slotDiv.className = `time-slot ${slot.available ? '' : 'unavailable'}`;
                slotDiv.textContent = slot.time;
                
                if (slot.available) {
                    slotDiv.addEventListener('click', function() {
                        document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                        this.classList.add('selected');
                        timeInput.value = slot.time;
                    });
                }
                
                timeSlotsContainer.appendChild(slotDiv);
            });
        } else {
            timeSlotsContainer.innerHTML = '<p style="color: var(--danger-color);">Ошибка загрузки слотов</p>';
        }
    } catch (error) {
        console.error('Ошибка загрузки слотов:', error);
        timeSlotsContainer.innerHTML = '<p style="color: var(--danger-color);">Ошибка загрузки</p>';
    }
}

// Отправка формы
document.getElementById('appointment-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        first_name: document.getElementById('first_name').value,
        last_name: document.getElementById('last_name').value,
        middle_name: document.getElementById('middle_name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        doctor_id: document.getElementById('doctor').value,
        appointment_date: document.getElementById('date').value,
        appointment_time: document.getElementById('time').value,
        service_id: document.getElementById('service').value,
        notes: document.getElementById('notes').value
    };
    
    // Валидация времени
    if (!data.appointment_time) {
        showAlert('Пожалуйста, выберите время приема', 'error');
        return;
    }
    
    try {
        const response = await fetch('api/create_appointment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            this.reset();
            document.getElementById('time-slots').innerHTML = '<p style="color: var(--text-light);">Выберите дату и врача</p>';
        } else {
            showAlert(result.error, 'error');
        }
    } catch (error) {
        showAlert('Произошла ошибка при записи. Пожалуйста, попробуйте позже.', 'error');
    }
});

// Показ уведомлений
function showAlert(message, type) {
    const container = document.getElementById('alert-container');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    container.innerHTML = '';
    container.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
