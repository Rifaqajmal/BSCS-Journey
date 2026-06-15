from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
size = comm.Get_size()

temperature = 20 + rank
humidity = 50 + rank
wind = 5 + rank

avg_temp = comm.allreduce(temperature, op=MPI.SUM) / size
avg_humidity = comm.allreduce(humidity, op=MPI.SUM) / size

max_wind = comm.allreduce(wind, op=MPI.MAX)

if rank == 0:
    print("Average Temperature:", avg_temp)
    print("Average Humidity:", avg_humidity)
    print("Maximum Wind Speed:", max_wind)