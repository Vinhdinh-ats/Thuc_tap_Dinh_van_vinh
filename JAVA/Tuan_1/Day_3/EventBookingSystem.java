import java.util.*;
import java.text.SimpleDateFormat;

class Event {
    private String eventId;
    private String eventName;
    private String location;
    private String date;
    private int maxSeats;

    public Event(String eventId, String eventName, String location, String date, int maxSeats) {
        this.eventId = eventId;
        this.eventName = eventName;
        this.location = location;
        this.date = date;
        this.maxSeats = maxSeats;
    }

    public String getEventId() { return eventId; }
    public String getEventName() { return eventName; }
    public String getDate() { return date; }
    public int getMaxSeats() { return maxSeats; }

    @Override
    public String toString() {
        return "Event ID: " + eventId + ", Name: " + eventName + ", Location: " + location +
               ", Date: " + date + ", Max Seats: " + maxSeats;
    }
}

class Booking {
    private String userEmail;
    private String eventId;
    private int seatNumber;
    private Date bookingTime;

    public Booking(String userEmail, String eventId, int seatNumber, Date bookingTime) {
        this.userEmail = userEmail;
        this.eventId = eventId;
        this.seatNumber = seatNumber;
        this.bookingTime = bookingTime;
    }

    public String getUserEmail() { return userEmail; }
    public String getEventId() { return eventId; }
    public int getSeatNumber() { return seatNumber; }

    @Override
    public String toString() {
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        return "Booking: Email: " + userEmail + ", Event ID: " + eventId +
               ", Seat: " + seatNumber + ", Time: " + sdf.format(bookingTime);
    }
}

public class EventBookingSystem {
    private static ArrayList<Event> events = new ArrayList<>();
    private static LinkedList<Booking> bookings = new LinkedList<>();
    private static HashMap<String, HashSet<Integer>> bookedSeats = new HashMap<>();
    private static Scanner scanner = new Scanner(System.in);

    public static void main(String[] args) {
        while (true) {
            displayMenu();
            String input = scanner.nextLine();
            int choice;
            try {
                choice = Integer.parseInt(input);
            } catch (NumberFormatException e) {
                System.out.println("Lựa chọn không hợp lệ! Vui lòng nhập số.");
                continue;
            }

            switch (choice) {
                case 1:
                    addEvent();
                    break;
                case 2:
                    bookTicket();
                    break;
                case 3:
                    viewBookings();
                    break;
                case 4:
                    searchEventsByName();
                    break;
                case 5:
                    sortEventsByName();
                    break;
                case 6:
                    showBookingStatistics();
                    break;
                case 0:
                    System.out.println("Thoát chương trình!");
                    return;
                default:
                    System.out.println("Lựa chọn không hợp lệ!");
            }
        }
    }

    private static void displayMenu() {
        System.out.println("\n=== HỆ THỐNG ĐẶT VÉ SỰ KIỆN ===");
        System.out.println("1. Thêm sự kiện mới");
        System.out.println("2. Đặt vé");
        System.out.println("3. Xem danh sách vé đã đặt");
        System.out.println("4. Tìm kiếm sự kiện theo tên");
        System.out.println("5. Sắp xếp sự kiện theo tên");
        System.out.println("6. Thống kê lượt đặt vé");
        System.out.println("0. Thoát");
        System.out.print("Nhập lựa chọn: ");
    }

    private static void addEvent() {
        System.out.print("Nhập ID sự kiện: ");
        String eventId = scanner.nextLine();
        if (findEvent(eventId) != null) {
            System.out.println("ID sự kiện đã tồn tại!");
            return;
        }

        System.out.print("Nhập tên sự kiện: ");
        String eventName = scanner.nextLine();
        System.out.print("Nhập địa điểm: ");
        String location = scanner.nextLine();
        System.out.print("Nhập ngày sự kiện (YYYY-MM-DD): ");
        String date = scanner.nextLine();
        // Kiểm tra định dạng ngày đơn giản
        if (!date.matches("\\d{4}-\\d{2}-\\d{2}")) {
            System.out.println("Định dạng ngày không hợp lệ (YYYY-MM-DD)!");
            return;
        }

        System.out.print("Nhập số ghế tối đa: ");
        String maxSeatsInput = scanner.nextLine();
        int maxSeats;
        try {
            maxSeats = Integer.parseInt(maxSeatsInput);
            if (maxSeats <= 0) {
                System.out.println("Số ghế phải lớn hơn 0!");
                return;
            }
        } catch (NumberFormatException e) {
            System.out.println("Số ghế không hợp lệ!");
            return;
        }

        Event event = new Event(eventId, eventName, location, date, maxSeats);
        events.add(event);
        bookedSeats.put(eventId, new HashSet<>());
        System.out.println("Thêm sự kiện thành công!");
    }

    private static void bookTicket() {
        System.out.print("Nhập email người dùng: ");
        String userEmail = scanner.nextLine();
        System.out.print("Nhập ID sự kiện: ");
        String eventId = scanner.nextLine();
        Event event = findEvent(eventId);
        if (event == null) {
            System.out.println("Sự kiện không tồn tại!");
            return;
        }

        System.out.print("Nhập số ghế (1-" + event.getMaxSeats() + "): ");
        String seatInput = scanner.nextLine();
        int seatNumber;
        try {
            seatNumber = Integer.parseInt(seatInput);
            if (seatNumber < 1 || seatNumber > event.getMaxSeats()) {
                System.out.println("Số ghế không hợp lệ!");
                return;
            }
        } catch (NumberFormatException e) {
            System.out.println("Số ghế không hợp lệ!");
            return;
        }

        HashSet<Integer> seats = bookedSeats.get(eventId);
        if (seats.contains(seatNumber)) {
            System.out.println("Ghế đã được đặt!");
            return;
        }

        seats.add(seatNumber);
        bookings.add(new Booking(userEmail, eventId, seatNumber, new Date()));
        System.out.println("Đặt vé thành công!");
    }

    private static void viewBookings() {
        if (bookings.isEmpty()) {
            System.out.println("Chưa có vé nào được đặt!");
            return;
        }

        System.out.println("\nDanh sách vé đã đặt:");
        Iterator<Booking> iterator = bookings.iterator();
        while (iterator.hasNext()) {
            System.out.println(iterator.next());
        }
    }

    private static void searchEventsByName() {
        System.out.print("Nhập tên sự kiện cần tìm: ");
        String searchName = scanner.nextLine().toLowerCase();
        boolean found = false;

        for (Event event : events) {
            if (event.getEventName().toLowerCase().contains(searchName)) {
                System.out.println(event);
                found = true;
            }
        }

        if (!found) {
            System.out.println("Không tìm thấy sự kiện nào!");
        }
    }

    private static void sortEventsByName() {
        if (events.isEmpty()) {
            System.out.println("Danh sách sự kiện rỗng!");
            return;
        }

        events.sort((e1, e2) -> e1.getEventName().compareToIgnoreCase(e2.getEventName()));
        System.out.println("Đã sắp xếp sự kiện theo tên!");
        System.out.println("\nDanh sách sự kiện:");
        for (Event event : events) {
            System.out.println(event);
        }
    }

    private static void showBookingStatistics() {
        HashMap<String, Integer> stats = new HashMap<>();
        for (Booking booking : bookings) {
            stats.put(booking.getEventId(), stats.getOrDefault(booking.getEventId(), 0) + 1);
        }

        if (stats.isEmpty()) {
            System.out.println("Chưa có lượt đặt vé nào!");
            return;
        }

        System.out.println("\nThống kê lượt đặt vé:");
        for (Map.Entry<String, Integer> entry : stats.entrySet()) {
            Event event = findEvent(entry.getKey());
            String eventName = (event != null) ? event.getEventName() : "Unknown";
            System.out.println("Sự kiện " + eventName + " (ID: " + entry.getKey() + "): " + entry.getValue() + " vé");
        }
    }

    private static Event findEvent(String eventId) {
        for (Event event : events) {
            if (event.getEventId().equals(eventId)) {
                return event;
            }
        }
        return null;
    }
}