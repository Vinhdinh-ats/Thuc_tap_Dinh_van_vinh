using Microsoft.EntityFrameworkCore;
using ToDoApp.Models;

namespace ToDoApp.Data
{
     public class AppDbContext : DbContext
     {
          public DbSet<ToDoApp.Models.Task> Tasks { get; set; }

          protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
          {
               optionsBuilder.UseSqlite("Data Source=todoapp.db");
          }
     }
}