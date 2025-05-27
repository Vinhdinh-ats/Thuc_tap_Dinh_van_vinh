using Day_7.Models;
using Day_7.Data;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Linq;
using System.Windows.Input;
using System;

namespace Day_7.ViewModels
{
     public class TodoViewModel : INotifyPropertyChanged
     {
          private string _title = string.Empty;
          private DateTime _deadline = DateTime.Now;
          private bool _isCompleted;
          private TodoItem? _selectedItem;
          // private string _searchText;



          public ObservableCollection<TodoItem> Todos { get; set; } = new();
          public ICommand AddCommand { get; }
          public ICommand UpdateCommand { get; }
          public ICommand DeleteCommand { get; }

          public string Title { get => _title; set { _title = value; OnPropertyChanged(nameof(Title)); } }
          public DateTime Deadline { get => _deadline; set { _deadline = value; OnPropertyChanged(nameof(Deadline)); } }
          public bool IsCompleted { get => _isCompleted; set { _isCompleted = value; OnPropertyChanged(nameof(IsCompleted)); } }

          public TodoItem? SelectedItem
          {
               get => _selectedItem;
               set
               {
                    _selectedItem = value;
                    if (value != null)
                    {
                         Title = value.Title;
                         Deadline = value.Deadline;
                         IsCompleted = value.IsCompleted;
                    }
                    OnPropertyChanged(nameof(SelectedItem));
               }
          }

          public event PropertyChangedEventHandler? PropertyChanged;
          private void OnPropertyChanged(string propertyName)
          {
               PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
          }

          public TodoViewModel()
          {
               LoadTodos();
               AddCommand = new RelayCommand(_ => AddTodo());
               UpdateCommand = new RelayCommand(_ => UpdateTodo(), _ => SelectedItem != null);
               DeleteCommand = new RelayCommand(_ => DeleteTodo(), _ => SelectedItem != null);

               _selectedItem = new TodoItem();

          }

          void LoadTodos()
          {
               using var db = new AppDbContext();
               db.Database.EnsureCreated();
               var items = db.TodoItems.ToList();
               Todos.Clear();
               foreach (var item in items)
                    Todos.Add(item);
          }

          void AddTodo()
          {
               var item = new TodoItem { Title = Title, Deadline = Deadline, IsCompleted = IsCompleted };
               using var db = new AppDbContext();
               db.TodoItems.Add(item);
               db.SaveChanges();
               LoadTodos();
          }

          void UpdateTodo()
          {
               if (SelectedItem == null)
                    return;
               using var db = new AppDbContext();
               var todo = db.TodoItems.Find(SelectedItem.Id);

               if (todo == null)
                    return;
               todo.Title = Title;
               todo.Deadline = Deadline;
               todo.IsCompleted = IsCompleted;
               db.SaveChanges();
               LoadTodos();
          }


          void DeleteTodo()
          {
               if (SelectedItem == null)
                    return;

               using var db = new AppDbContext();
               var todo = db.TodoItems.Find(SelectedItem.Id);
               if (todo == null)
                    return;

               db.TodoItems.Remove(todo);
               db.SaveChanges();
               LoadTodos();
          }
     }
}
