<table>
   <thead>
     <tr>
       <th scope="col">#</th>
       <th scope="col">Nombre</th>
       <th scope="col">Correo</th>
     </tr>
   </thead>
   <tbody>
       @foreach($usuarios as $key => $usuario)
           <tr>
               <td>{{ $usuario->id }}</td>
               <td>{{ $usuario->name }}</td>
               <td>{{ $usuario->email }}</td>
           </tr>
       @endforeach
   </tbody>
</table>
