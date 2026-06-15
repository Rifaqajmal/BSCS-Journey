from mpi4py import MPI
import numpy as np

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
size = comm.Get_size()

value = np.array(rank + 1, dtype='i')

result = np.array(0, dtype='i')

req = comm.Iallreduce(value, result, op=MPI.SUM)
req.Wait()

average = result / size

print("Process", rank, "Average:", average)