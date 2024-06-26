<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Наши услуги</title>
    <link rel="stylesheet" href="assets/css/service.css">
</head>
<body>
    <header style="background-image: url('https://via.placeholder.com/1500x600/FF5733/000000/?text=Event+Planner');">
        <h1>Планирование вечеринок и событий</h1>
    </header>
    <nav>
        <a href="main.php">Главная</a>
        <a href="about.php">О нас</a>
        <a href="service.php">Наши услуги</a>
        <?php
        if(isset($_SESSION['username'])){
            echo '<div class="user-info">';
            echo '<a href="account.php">';
            echo '<button>' . $_SESSION['username'] . '</button>';
            echo '</a>';
            echo '</div>';
            echo '<form action="logout.php" method="post" style="display:inline;">';
            echo '<button type="submit" name="logout">Выйти</button>';
            echo '</form>';
        } else {
            echo '<a href="login.html">Войти</a>';
        }
        ?>
    </nav>
    <section>
        <h2>Наши услуги</h2>
        <p>Мы предлагаем полный спектр услуг по планированию и организации мероприятий, включая:</p>
        <ul>
            <li>Консультации по планированию мероприятий</li>
            <li>Организация и координация мероприятий</li>
            <li>Декорации и украшения</li>
            <li>Предоставление профессиональной музыки и развлечений</li>
            <li>Кейтеринг и обслуживание гостей</li>
            <li>И многое другое!</li>
        </ul>
        <form id="orderForm">
            <label for="service">Выберите услугу:</label>
            <select name="service" id="service">
                <option value="consultation">Консультация по планированию мероприятий</option>
                <option value="organization">Организация и координация мероприятий</option>
                <option value="decorations">Декорации и украшения</option>
                <option value="music">Предоставление профессиональной музыки и развлечений</option>
                <option value="catering">Кейтеринг и обслуживание гостей</option>
            </select>
            <label for="name">Ваше имя:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Ваш Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit" onclick="submitOrder()">Заказать услугу</button>
        </form>
    </section>
    
    <section>
        <h2>Планирование вечеринки</h2>
        <p>Вы можете связаться с нами для организации вашей следующей вечеринки! Заполните форму ниже, и мы свяжемся с вами в ближайшее время:</p>
        <form id="partyForm">
            <label for="party-type">Тип вечеринки:</label>
            <input type="text" id="party-type" name="party-type" required>
            <label for="date">Дата вечеринки:</label>
            <input type="date" id="date" name="date" required>
            <label for="guests">Количество гостей:</label>
            <input type="number" id="guests" name="guests" required>
            <label for="additional-info">Дополнительная информация:</label>
            <textarea id="additional-info" name="additional-info"></textarea>
            <button type="submit" onclick="submitParty()">Забронировать вечеринку</button>
        </form>
    </section>
    
    <section>
        <h2>Контакты для организаторов</h2>
        <p>Если вы заинтересованы в сотрудничестве или у вас есть вопросы, пожалуйста, свяжитесь с нами:</p>
        <p>Email: info@yourpartyplanners.com</p>
        <p>Телефон: +1234567890</p>
    </section>
    <section>
        <div class="events-container">
            <?php
           $servername = "sql7.freemysqlhosting.net"; // Имя сервера БД
           $username = "sql7709451"; // Имя пользователя БД
           $password = "4bisLes7Cr"; // Пароль к БД
           $dbname = "sql7709451"; // Имя вашей БД

            $conn = new mysqli($servername, $username, $password, $dbname);
            mysqli_set_charset($conn, "utf8");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT Events.EventID, Events.EventName, Events.EventDate, Events.EventDescription, Events.EventPoster, Venues.VenueName, Venues.VenueAddress
                    FROM Events
                    INNER JOIN Venues ON Events.VenueID = Venues.VenueID";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='event'>";
                    if (!empty($row["EventPoster"])) {
                        echo "<img src='" . $row["EventPoster"] . "' alt='" . $row["EventName"] . "' class='poster'>";
                    }
                    echo "<h3>" . $row["EventName"] . "</h3>";
                    echo "<p><strong>Дата:</strong> " . $row["EventDate"] . "</p>";
                    echo "<p><strong>Описание:</strong> " . $row["EventDescription"] . "</p>";
                    echo "<p><strong>Место:</strong> " . $row["VenueName"] . "</p>";
                    echo "<p><strong>Адрес:</strong> " . $row["VenueAddress"] . "</p>";
                    echo "<a href='book_tickets.php?event_id=" . $row["EventID"] . "'><button>Забронировать билеты</button></a>";
                    echo "</div>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>
        </div>
    </section>

    <script>
        function submitOrder() {
            var formData = new FormData(document.getElementById("orderForm"));
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "process_order.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                }
            };
            xhr.send(formData);
        }

        function submitParty() {
            var formData = new FormData(document.getElementById("partyForm"));
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "process_party.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                }
            };
            xhr.send(formData);
        }
    </script>
</body>
</html>
