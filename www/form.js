document.getElementById("excursionForm").addEventListener("submit", function(e) {    
    // Собираем данные формы
    const formData = new FormData(this);
    
    // Оставляем ТОЛЬКО alert для предпросмотра
    const name = formData.get('name');
    const routeDisplay = {
        "historic": "Рыбная деревня",
        "museum": "Амалиенау",
        "parks": "Подземелья и оборонительные валы", 
        "architecture": "Куршкая коса"
    }[formData.get('route')];
    
    alert(`Спасибо, ${name}! Вы записаны на экскурсию "${routeDisplay}"`);
});