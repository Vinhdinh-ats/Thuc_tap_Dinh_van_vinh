using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;

// Enum định nghĩa trạng thái đơn hàng
public enum OrderStatus
{
     Created,
     Preparing,
     ReadyForDelivery,
     Delivering,
     Delivered,
     Cancelled,
     Failed
}

// Class chứa thông tin sự kiện đơn hàng
public class OrderEventArgs : EventArgs
{
     public Order Order { get; }
     public OrderStatus NewStatus { get; }

     public OrderEventArgs(Order order, OrderStatus newStatus)
     {
          Order = order;
          NewStatus = newStatus;
     }
}

// Class Order - chủ thể trong Observer pattern
public class Order
{
     public int OrderId { get; }
     public string CustomerName { get; }
     private OrderStatus _status;

     // Sự kiện sử dụng EventHandler chuẩn, đánh dấu nullable
     public event EventHandler<OrderEventArgs>? OrderStatusChanged;

     public Order(int orderId, string customerName)
     {
          OrderId = orderId;
          CustomerName = customerName;
          _status = OrderStatus.Created;
     }

     public OrderStatus Status
     {
          get => _status;
          private set
          {
               _status = value;
               OnOrderStatusChanged(new OrderEventArgs(this, _status));
          }
     }

     protected virtual void OnOrderStatusChanged(OrderEventArgs e)
     {
          OrderStatusChanged?.Invoke(this, e);
     }

     public void UpdateStatus(OrderStatus newStatus)
     {
          Status = newStatus;
     }

     public override string ToString() => $"Đơn hàng #{OrderId} - Khách: {CustomerName} - Trạng thái: {Status}";
}

// Observer: Bộ phận bếp
public class Kitchen
{
     public Kitchen(Order order)
     {
          order.OrderStatusChanged += (sender, e) =>
          {
               if (e.NewStatus == OrderStatus.Created)
               {
                    Console.WriteLine($"[Bếp] Nhận được đơn hàng mới #{e.Order.OrderId}. Bắt đầu chuẩn bị...");
               }
          };
     }
}

// Observer: Bộ phận giao hàng
public class Delivery
{
     public Delivery(Order order)
     {
          order.OrderStatusChanged += (sender, e) =>
          {
               if (e.NewStatus == OrderStatus.ReadyForDelivery)
               {
                    Console.WriteLine($"[Giao hàng] Đơn hàng #{e.Order.OrderId} đã sẵn sàng. Shipper nhận đơn!");
               }
               else if (e.NewStatus == OrderStatus.Failed)
               {
                    Console.WriteLine($"[Giao hàng] Đơn hàng #{e.Order.OrderId} giao thất bại!");
               }
          };
     }
}

// Observer: Bộ phận CSKH
public class CustomerService
{
     public CustomerService(Order order)
     {
          order.OrderStatusChanged += (sender, e) =>
          {
               if (e.NewStatus == OrderStatus.Cancelled)
               {
                    Console.WriteLine($"[CSKH] Đơn hàng #{e.Order.OrderId} đã bị hủy. Liên hệ khách hàng!");
               }
               else if (e.NewStatus == OrderStatus.Failed)
               {
                    Console.WriteLine($"[CSKH] Đơn hàng #{e.Order.OrderId} giao thất bại. Xử lý hoàn tiền!");
               }
          };
     }
}

// Class chính để chạy mô phỏng
public class Program
{
     // Predicate kiểm tra đơn hàng đang giao
     public static Predicate<Order> IsDelivering = order => order.Status == OrderStatus.Delivering;

     // Func chuyển thông tin đơn hàng thành chuỗi
     public static Func<Order, string> GetOrderDescription = order =>
         $"Đơn hàng #{order.OrderId} của {order.CustomerName} đang ở trạng thái {order.Status}";

     // Action để log trạng thái
     public static Action<string> LogStatus = message =>
     {
          Console.WriteLine($"[LOG] {message}");
          File.AppendAllText("order_log.txt", $"{DateTime.Now}: {message}\n");
     };

     public static void Main()
     {
          // Tạo danh sách đơn hàng
          List<Order> orders = new List<Order>
        {
            new Order(1, "Nguyễn Văn A"),
            new Order(2, "Trần Thị B"),
            new Order(3, "Lê Văn C")
        };

          // Đăng ký observers cho mỗi đơn hàng
          foreach (var order in orders)
          {
               new Kitchen(order);
               new Delivery(order);
               new CustomerService(order);

               // Đăng ký handler dùng lambda để log trạng thái
               order.OrderStatusChanged += (sender, e) =>
               {
                    LogStatus(GetOrderDescription(e.Order));
               };
          }

          // Mô phỏng thay đổi trạng thái
          Console.WriteLine("=== Mô phỏng thay đổi trạng thái ===");
          orders[0].UpdateStatus(OrderStatus.Created);
          orders[0].UpdateStatus(OrderStatus.ReadyForDelivery);
          orders[0].UpdateStatus(OrderStatus.Delivering);
          orders[0].UpdateStatus(OrderStatus.Delivered);

          orders[1].UpdateStatus(OrderStatus.Created);
          orders[1].UpdateStatus(OrderStatus.ReadyForDelivery);
          orders[1].UpdateStatus(OrderStatus.Delivering);
          orders[1].UpdateStatus(OrderStatus.Failed);

          orders[2].UpdateStatus(OrderStatus.Created);
          orders[2].UpdateStatus(OrderStatus.Cancelled);

          // Thống kê với LINQ
          Console.WriteLine("\n=== Thống kê đơn hàng ===");
          int deliveredCount = orders.Count(o => o.Status == OrderStatus.Delivered);
          int cancelledCount = orders.Count(o => o.Status == OrderStatus.Cancelled);
          int failedCount = orders.Count(o => o.Status == OrderStatus.Failed);
          int deliveringCount = orders.Count(o => o.Status == OrderStatus.Delivering);

          Console.WriteLine($"Số đơn giao thành công: {deliveredCount}");
          Console.WriteLine($"Số đơn bị hủy: {cancelledCount}");
          Console.WriteLine($"Số đơn giao thất bại: {failedCount}");
          Console.WriteLine($"Số đơn đang giao: {deliveringCount}");
     }
}