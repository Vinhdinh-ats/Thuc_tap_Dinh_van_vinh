using System;
using ToDoApp.Services;

namespace ToDoApp
{
     class Program
     {
          static void Main(string[] args)
          {
               var taskService = new TaskService();
               while (true)
               {
                    Console.WriteLine("\nTo-Do App");
                    Console.WriteLine("1. Add Task");
                    Console.WriteLine("2. List Tasks");
                    Console.WriteLine("3. Update Task");
                    Console.WriteLine("4. Delete Task");
                    Console.WriteLine("5. Filter Tasks by Status");
                    Console.WriteLine("6. Report Completed Tasks");
                    Console.WriteLine("7. Exit");
                    Console.Write("Choose an option: ");

                    var choice = Console.ReadLine();
                    try
                    {
                         switch (choice)
                         {
                              case "1":
                                   Console.Write("Title: ");
                                   var title = Console.ReadLine();
                                   Console.Write("Description: ");
                                   var description = Console.ReadLine();
                                   Console.Write("Due Date (yyyy-MM-dd): ");
                                   if (DateTime.TryParse(Console.ReadLine(), out var dueDate))
                                   {
                                        taskService.AddTask("Some title", "Some description", dueDate);
                                   }
                                   else
                                   {
                                        Console.WriteLine("Invalid date format.");
                                   }
                                   break;

                              case "2":
                                   taskService.ListTasks();
                                   break;

                              case "3":
                                   Console.Write("Task ID: ");
                                   if (int.TryParse(Console.ReadLine(), out var id))
                                   {
                                        Console.Write("New Title (leave empty to skip): ");
                                        var newTitle = Console.ReadLine();
                                        Console.Write("New Description (leave empty to skip): ");
                                        var newDesc = Console.ReadLine();
                                        Console.Write("New Due Date (yyyy-MM-dd, leave empty to skip): ");
                                        DateTime? newDueDate = null;
                                        var dueDateInput = Console.ReadLine();
                                        if (!string.IsNullOrEmpty(dueDateInput) && DateTime.TryParse(dueDateInput, out var parsedDueDate))
                                        {
                                             newDueDate = parsedDueDate;
                                        }
                                        Console.Write("Is Completed (true/false, leave empty to skip): ");
                                        bool? isCompleted = null;
                                        var completedInput = Console.ReadLine();
                                        if (!string.IsNullOrEmpty(completedInput) && bool.TryParse(completedInput, out var parsedCompleted))
                                        {
                                             isCompleted = parsedCompleted;
                                        }
                                        taskService.UpdateTask(id, newTitle, newDesc, newDueDate, isCompleted);
                                   }
                                   else
                                   {
                                        Console.WriteLine("Invalid ID.");
                                   }
                                   break;

                              case "4":
                                   Console.Write("Task ID: ");
                                   if (int.TryParse(Console.ReadLine(), out var deleteId))
                                   {
                                        taskService.DeleteTask(deleteId);
                                   }
                                   else
                                   {
                                        Console.WriteLine("Invalid ID.");
                                   }
                                   break;

                              case "5":
                                   Console.Write("Show completed tasks? (true/false): ");
                                   if (bool.TryParse(Console.ReadLine(), out var status))
                                   {
                                        taskService.FilterTasksByStatus(status);
                                   }
                                   else
                                   {
                                        Console.WriteLine("Invalid input.");
                                   }
                                   break;

                              case "6":
                                   Console.Write("Report period (week/month): ");
                                   var period = Console.ReadLine();
                                   taskService.ReportCompletedTasks(period);
                                   break;

                              case "7":
                                   return;

                              default:
                                   Console.WriteLine("Invalid option.");
                                   break;
                         }
                    }
                    catch (Exception ex)
                    {
                         Console.WriteLine($"Error: {ex.Message}");
                    }
               }
          }
     }
}