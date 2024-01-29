import * as yup from "yup";

export const esquemaSucursal = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre de la sucursal es obligatorio")
    .max(50, "El nombre no puede tener más de 50 caracteres")
    .matches(
      /^[a-zA-Z0-9\s]+$/,
      "El nombre no puede contener caracteres especiales"
    ),

  direccion: yup.string().required("La dirección es obligatoria"),

  correo: yup
    .string()
    .email("Ingrese una dirección de correo electrónico válida")
    .required("El correo es obligatorio"),

  telefono: yup
    .string()
    .required("El teléfono es obligatorio")
    .matches(/^[0-9]{8}$/, "El teléfono debe contener exactamente 8 números"),

  departamento: yup.string().required("El departamento es obligatorio"),
});

export const esquemaMoneda = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre de la moneda es obligatorio")
    .max(50, "El nombre no puede tener más de 50 caracteres")
    .matches(
      /^[a-zA-Z0-9\s]+$/,
      "El nombre no puede contener caracteres especiales"
    ),

  pais: yup.string().required("El país es obligatorio"),
  simbolo: yup.string().required("El código de la moneda es obligatorio"),

  tipo_cambio: yup
    .number()
    .typeError("El tipo de cambio debe ser un número")
    .required("El tipo de cambio es obligatorio")
    .min(1, "El tipo de cambio no puede ser igual o menor a cero"),
});

export const esquemaKits = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre de la moneda es obligatorio")
    .max(50, "El nombre no puede tener más de 50 caracteres")
    .matches(
      /^[a-zA-Z0-9\s]+$/,
      "El nombre no puede contener caracteres especiales"
    ),
});

export const esquemaOfertas = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre es obligatorio")
    .max(50, "El nombre no puede tener más de 50 caracteres"),
  porcentaje: yup
    .number()
    .required("El porcentaje es obligatorio")
    .min(0, "El porcentaje no puede ser menor que 0")
    .max(100, "El porcentaje no puede ser mayor que 100")
    .typeError("Debe ser un número"),
  fecha_final: yup.string().required("La fecha final es obligatoria"),
});
