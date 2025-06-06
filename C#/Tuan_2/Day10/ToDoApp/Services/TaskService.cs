using System;
using System.Collections.Generic;
using System.Linq;
using ToDoApp.Data;
using ToDoApp.Models;
using ConsoleTables;

namespace ToDoApp.Services
{
     public class TaskService
     {
          private readonly AppDbContext _context;

          public TaskService()
          {
               _context = new AppDbContext();
               _context.Database.EnsureCreated();
          }

          // CRUD: Create
          public void AddTask(string title, string description, DateTime dueDate)
          {
               var task = new ToDoApp.Models.Task
               {
                    Title = title,
                    Description = description,
                    DueDate = dueDate,
                    CreatedAt = DateTime.Now,
                    IsCompleted = false
               };

               _context.Tasks.Add(task);
               _context.SaveChanges();
               Console.WriteLine("Task added successfully!");
          }

          // CRUD: Read (List all tasks)
          public void ListTasks()
          {
               var tasks = _context.Tasks.ToList();
               if (!tasks.Any())
               {
                    Console.WriteLine("No tasks found.");
                    return;
               }

               var table = new ConsoleTable("ID", "Title", "Description", "Due Date", "Completed", "Created At");
               foreach (var task in tasks)
               {
                    table.AddRow(task.Id, task.Title, task.Description, task.DueDate.ToString("yyyy-MM-dd"),
                                task.IsCompleted ? "Yes" : "No", task.CreatedAt.ToString("yyyy-MM-dd"));
               }
               table.Write();
          }

          // CRUD: Update
          public void UpdateTask(int id, string title, string description, DateTime? dueDate, bool? isCompleted)
          {

               if (title == null && description == null) throw new ArgumentNullException();
               var task = _context.Tasks.Find(id);
               if (task == null)
               {
                    throw new ArgumentException("Task not found.");
               }

               if (!string.IsNullOrWhiteSpace(title)) task.Title = title;
               if (!string.IsNullOrWhiteSpace(description)) task.Description = description;
               if (dueDate.HasValue) task.DueDate = dueDate.Value;
               if (isCompleted.HasValue) task.IsCompleted = isCompleted.Value;

               _context.SaveChanges();
               Console.WriteLine("Task updated successfully!");
          }

          // CRUD: Delete
          public void DeleteTask(int id)
          {
               var task = _context.Tasks.Find(id);
               if (task == null)
               {
                    throw new ArgumentException("Task not found.");
               }

               _context.Tasks.Remove(task);
               _context.SaveChanges();
               Console.WriteLine("Task deleted successfully!");
          }

          // Filter tasks by status
          public void FilterTasksByStatus(bool isCompleted)
          {
               var tasks = _context.Tasks.Where(t => t.IsCompleted == isCompleted).ToList();
               if (!tasks.Any())
               {
                    Console.WriteLine($"No {(isCompleted ? "completed" : "pending")} tasks found.");
                    return;
               }

               var table = new ConsoleTable("ID", "Title", "Description", "Due Date", "Completed");
               foreach (var task in tasks)
               {
                    table.AddRow(task.Id, task.Title, task.Description, task.DueDate.ToString("yyyy-MM-dd"),
                                task.IsCompleted ? "Yes" : "No");
               }
               table.Write();
          }

          // Count completed tasks by week/month
          public void ReportCompletedTasks(string period)
          {
               if (period == null) throw new ArgumentNullException(nameof(period));
               var tasks = _context.Tasks.Where(t => t.IsCompleted).ToList();
               if (!tasks.Any())
               {
                    Console.WriteLine("No completed tasks found.");
                    return;
               }

               if (period.ToLower() == "week")
               {
                    var grouped = tasks.GroupBy(t => t.CreatedAt.Year * 100 + GetWeekOfYear(t.CreatedAt))
                                      .Select(g => new { Period = $"Week {g.Key % 100}, {g.Key / 100}", Count = g.Count() });
                    DisplayReport(grouped, "Weekly Completed Tasks");
               }
               else if (period.ToLower() == "month")
               {
                    var grouped = tasks.GroupBy(t => t.CreatedAt.ToString("yyyy-MM"))
                                      .Select(g => new { Period = g.Key, Count = g.Count() });
                    DisplayReport(grouped, "Monthly Completed Tasks");
               }
          }

          private void DisplayReport(IEnumerable<dynamic> grouped, string title)
          {
               var table = new ConsoleTable("Period", "Completed Tasks");
               foreach (var item in grouped)
               {
                    table.AddRow(item.Period, item.Count);
               }
               Console.WriteLine($"\n{title}");
               table.Write();
          }

          private int GetWeekOfYear(DateTime date)
          {
               return System.Globalization.CultureInfo.InvariantCulture.Calendar.GetWeekOfYear(
                   date, System.Globalization.CalendarWeekRule.FirstDay, DayOfWeek.Monday);
          }
     }
}