<?php
    session_start();

    // Include database configuration
    require_once("../Home/config.php");

    // Create a database connection
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

    // Check if connection was successful
    if ($conn->connect_error) {
        die("<p class=\"error\">Connection to the database failed: " . $conn->connect_error . "</p>");
    }

    // Fetch total bookings count
    $sqlTotalBookings = "SELECT COUNT(*) AS total FROM bookings";
    $resultTotalBookings = $conn->query($sqlTotalBookings);
    $totalBookings = ($resultTotalBookings && $resultTotalBookings->num_rows > 0) ? $resultTotalBookings->fetch_assoc()['total'] : 0;

    // Fetch total passengers count
    $sqlTotalPassengers = "SELECT COUNT(*) AS total FROM passengers";
    $resultTotalPassengers = $conn->query($sqlTotalPassengers);
    $totalPassengers = ($resultTotalPassengers && $resultTotalPassengers->num_rows > 0) ? $resultTotalPassengers->fetch_assoc()['total'] : 0;

    // Fetch total revenue
    $sqlTotalRevenue = "SELECT SUM(total_price) AS total_revenue FROM bookings WHERE payment_status = 'paid'";
    $resultTotalRevenue = $conn->query($sqlTotalRevenue);
    $totalRevenue = ($resultTotalRevenue && $resultTotalRevenue->num_rows > 0) ? $resultTotalRevenue->fetch_assoc()['total_revenue'] : 0;

    // Fetch bookings by class
    $sqlBookingsByClass = "SELECT class, COUNT(*) AS count FROM bookings GROUP BY class";
    $resultBookingsByClass = $conn->query($sqlBookingsByClass);
    $bookingsByClass = [];
    if ($resultBookingsByClass && $resultBookingsByClass->num_rows > 0) {
        while($row = $resultBookingsByClass->fetch_assoc()) {
            $bookingsByClass[$row['class']] = $row['count'];
        }
    }

    // Fetch bookings by month
    $sqlBookingsByMonth = "SELECT MONTHNAME(booking_date) AS month, COUNT(*) AS count 
                           FROM bookings 
                           GROUP BY MONTH(booking_date) 
                           ORDER BY MONTH(booking_date)";
    $resultBookingsByMonth = $conn->query($sqlBookingsByMonth);
    $bookingsByMonth = [];
    $months = [];
    if ($resultBookingsByMonth && $resultBookingsByMonth->num_rows > 0) {
        while($row = $resultBookingsByMonth->fetch_assoc()) {
            $bookingsByMonth[] = $row['count'];
            $months[] = $row['month'];
        }
    }

    // Fetch passengers by nationality
    $sqlPassengersByNationality = "SELECT nationality, COUNT(*) AS count 
                                   FROM passengers 
                                   GROUP BY nationality 
                                   ORDER BY count DESC 
                                   LIMIT 10"; // Top 10 nationalities
    $resultPassengersByNationality = $conn->query($sqlPassengersByNationality);
    $passengersByNationality = [];
    if ($resultPassengersByNationality && $resultPassengersByNationality->num_rows > 0) {
        while($row = $resultPassengersByNationality->fetch_assoc()) {
            $passengersByNationality[$row['nationality']] = $row['count'];
        }
    }

    // Fetch flights from the database
    $sqlFlights = "SELECT f.flight_id,  -- Include flight_id here
        f.flight_number, 
        CONCAT(da.airport_name, ', ', da.city) AS origin, 
        CONCAT(aa.airport_name, ', ', aa.city) AS destination, 
        f.departure_time, 
        f.arrival_time
    FROM flights f
    JOIN airports da ON f.departure_airport_id = da.airport_id
    JOIN airports aa ON f.arrival_airport_id = aa.airport_id;";

    $resultFlights = $conn->query($sqlFlights);

    // Simplified Bookings Query: Fetch bookings without joining with airports and flights
    $sqlBookings = "SELECT 
        b.booking_id, 
        CONCAT(p.first_name, ' ', p.last_name) AS passenger_name, 
        b.departure, 
        b.destination, 
        b.class, 
        b.payment_status
        FROM bookings b
        JOIN passengers p ON b.passenger_id = p.passenger_id
        ORDER BY b.booking_date DESC";
    $resultBookings = $conn->query($sqlBookings);

    // Fetch users/passengers from the database
    $sqlUsers = "SELECT passenger_id, 
        CONCAT(first_name, ' ', last_name) AS name, 
        email, 
        username,
        is_deleted -- Include is_deleted in the selection
        FROM passengers
        ORDER BY passenger_id ASC";
    $resultUsers = $conn->query($sqlUsers);

    // Close the database connection
    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airline Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <!-- Include Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h1>Admin Panel</h1>
            <ul>
                <li><a href="#" class="active" data-section="dashboard">Dashboard</a></li>
                <li><a href="#" data-section="flights">Flights</a></li>
                <li><a href="#" data-section="bookings">Bookings</a></li>
                <li><a href="#" data-section="users">Users</a></li>
            </ul>

            <div class="logout-container">
                <form action="../Security/logout.php" method="POST">
                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Log Off</button>
                </form>
            </div>
        </nav>

        <main class="content">
            <section id="dashboard" class="active">
                <h2>Dashboard</h2>
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <h3>Total Bookings</h3>
                        <p><?php echo number_format($totalBookings); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Passengers</h3>
                        <p><?php echo number_format($totalPassengers); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Revenue</h3>
                        <p>R<?php echo number_format($totalRevenue, 2); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Upcoming Flights</h3>
                        <p>42</p>
                    </div>
                </div>
                <div class="dashboard-charts">
                    <div class="chart-container">
                        <h3>Bookings by Class</h3>
                        <canvas id="bookingsByClassChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <h3>Bookings by Month</h3>
                        <canvas id="bookingsByMonthChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <h3>Top Passengers by Nationality</h3>
                        <canvas id="passengersByNationalityChart"></canvas>
                    </div>
                    <!-- Add more charts as needed -->
                </div>
            </section>
            <section id="flights">
                <h2>Flight Management</h2>
                <button id="addFlightBtn" class="btn">Add New Flight</button>
                <table id="flightTable">
                    <thead>
                        <tr>
                            <th>Flight Number</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Departure Date</th>
                            <th>Departure Time</th>
                            <th>Arrival Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultFlights && $resultFlights->num_rows > 0): ?>
                            <?php while($row = $resultFlights->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['flight_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['origin']); ?></td>
                                    <td><?php echo htmlspecialchars($row['destination']); ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($row['departure_time'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($row['departure_time'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($row['arrival_time'])); ?></td>
                                    <td>
                                        <!-- <button class="btn">Edit</button> -->
                                        <?php
                                        // Check if the flight has passed
                                        if (strtotime($row['arrival_time']) < time()): ?>
                                            <form action="delete_flight.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="flight_id" value="<?php echo $row['flight_id']; ?>">
                                                <button type="submit" class="btn" onclick="return confirm('Are you sure you want to delete this flight?');">Delete</button>
                                            </form>
                                        <?php else: ?>
                                            <span>N/A</span> <!-- Not available for deletion -->
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No flights available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
            <section id="bookings">
    <h2>Booking Management</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Passenger Name</th>
                <th>Departure</th>
                <th>Destination</th>
                <th>Class</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultBookings && $resultBookings->num_rows > 0): ?>
                <?php while($row = $resultBookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['passenger_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['departure']); ?></td>
                        <td><?php echo htmlspecialchars($row['destination']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($row['class'])); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($row['payment_status'])); ?></td>
                        <td>
                            <?php if ($row['payment_status'] !== 'canceled'): ?>
                                <form action="cancel_booking.php" method="POST">
                                    <input type="hidden" name="cancel_booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                    <button type="submit" class="btn" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</button>
                                </form>
                            <?php else: ?>
                                <span>Canceled</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No bookings available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

        <section id="users">
            <h2>User Management</h2>
            <button id="addUserBtn" class="btn">Add New User</button>
            <table id="userTable">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultUsers && $resultUsers->num_rows > 0): ?>
                        <?php while($row = $resultUsers->fetch_assoc()): ?>
                            <?php if ($row['is_deleted'] == 0): // Only show users that are not deleted ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['passenger_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <?php
                                            if (!empty($row['username'])) {
                                                // Example logic for roles
                                                if ($row['username'] === 'admin@airline.com') {
                                                    echo 'Admin';
                                                } else {
                                                    echo 'Staff';
                                                }
                                            } else {
                                                echo 'Customer';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <!-- <button class="btn">Edit</button> -->
                                        <form action="soft_delete_passengers.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="passenger_id" value="<?php echo htmlspecialchars($row['passenger_id']); ?>">
                                            <button type="submit" class="btn" onclick="return confirm('Are you sure you want to soft delete this passenger?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No users available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        </main>
    </div>

    <!-- Add Flight Modal -->
    <div id="addFlightModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Flight</h2>
            <form id="addFlightForm">
                <label for="flightNumber">Flight Number:</label>
                <input type="text" id="flightNumber" name="flightNumber" required>

                <label for="origin">Origin:</label>
                <input type="text" id="origin" name="origin" required>

                <label for="destination">Destination:</label>
                <input type="text" id="destination" name="destination" required>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="time">Time:</label>
                <input type="time" id="time" name="time" required>

                <button type="submit" class="btn">Add Flight</button>
            </form>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New User</h2>
            <form id="addUserForm">
                <label for="userName">Name:</label>
                <input type="text" id="userName" name="userName" required>

                <label for="userEmail">Email:</label>
                <input type="email" id="userEmail" name="userEmail" required>

                <label for="userRole">Role:</label>
                <select id="userRole" name="userRole" required>
                    <option value="Admin">Admin</option>
                    <option value="Staff">Staff</option>
                    <option value="Customer">Customer</option>
                </select>

                <button type="submit" class="btn">Add User</button>
            </form>
        </div>
    </div>

    <!-- JavaScript to Render Charts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bookings by Class Chart
            var ctx1 = document.getElementById('bookingsByClassChart').getContext('2d');
            var bookingsByClassChart = new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_map('ucfirst', array_keys($bookingsByClass))); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_values($bookingsByClass)); ?>,
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF'
                        ],
                        hoverBackgroundColor: [
                            '#FF6384CC',
                            '#36A2EBCC',
                            '#FFCE56CC',
                            '#4BC0C0CC',
                            '#9966FFCC'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: false,
                            text: 'Bookings by Class'
                        }
                    }
                }
            });

            // Bookings by Month Chart
            var ctx2 = document.getElementById('bookingsByMonthChart').getContext('2d');
            var bookingsByMonthChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($months); ?>,
                    datasets: [{
                        label: 'Number of Bookings',
                        data: <?php echo json_encode($bookingsByMonth); ?>,
                        backgroundColor: '#36A2EB',
                        borderColor: '#36A2EB',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision:0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: false,
                            text: 'Bookings by Month'
                        }
                    }
                }
            });

            // Passengers by Nationality Chart
            var ctx3 = document.getElementById('passengersByNationalityChart').getContext('2d');
            var passengersByNationalityChart = new Chart(ctx3, {
                type: 'bar', // Chart.js v3+ uses 'bar' with indexAxis for horizontal
                data: {
                    labels: <?php echo json_encode(array_keys($passengersByNationality)); ?>,
                    datasets: [{
                        label: 'Number of Passengers',
                        data: <?php echo json_encode(array_values($passengersByNationality)); ?>,
                        backgroundColor: '#FFCE56',
                        borderColor: '#FFCE56',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // This makes the bar chart horizontal
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision:0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: false,
                            text: 'Passengers by Nationality'
                        }
                    }
                }
            });
        });
    </script>

    <script src="admin.js"></script>
</body>
</html>
